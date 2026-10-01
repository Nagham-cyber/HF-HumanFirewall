<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Carbon\Carbon;
use App\Models\Admin;
use App\Models\AdminAuditLog;

class AdminAuthController extends Controller
{
    /**
     * عرض صفحة تسجيل الدخول
     */
    public function showLoginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    /**
     * معالجة تسجيل الدخول
     */
    public function login(Request $request)
    {
        // ==================== 1. Validation ====================
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // ==================== 2. Log Attempt ====================
        try {
            \DB::table('login_attempts')->insert([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'successful' => false,
                'guard' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // تجاهل إذا الجدول غير موجود
        }

        // ==================== 3. Rate Limiting ====================
        $key = 'admin-login:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->withErrors([
                'email' => "Too many login attempts. Try again in {$seconds} seconds.",
            ]);
        }

        // ==================== 4. Find Admin ====================
        $admin = Admin::where('email', $request->email)->first();

        // ==================== 5. Verify Credentials ====================
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            RateLimiter::hit($key, 900);

            if ($admin) {
                $admin->increment('failed_attempts');
                if ($admin->failed_attempts >= 5) {
                    $admin->update(['locked_until' => Carbon::now()->addMinutes(30)]);
                }
            }

            AdminAuditLog::create([
                'action' => 'login_failed',
                'description' => "Failed login attempt for: {$request->email}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
        }

        // ==================== 6. Check Active ====================
        if (!$admin->is_active) {
            return back()->withErrors(['email' => 'Account is deactivated.']);
        }

        // ==================== 7. Check Locked ====================
        if ($admin->isLocked()) {
            return back()->withErrors(['email' => 'Account locked. Try again later.']);
        }

        // ==================== 8. 2FA (Optional) ====================
        if ($admin->two_factor_enabled) {
            $code = rand(100000, 999999);
            $admin->update([
                'two_factor_code' => $code,
                'two_factor_expires_at' => Carbon::now()->addMinutes(10),
            ]);

            session([
                '2fa_admin_id' => $admin->id,
                '2fa_remember' => $request->filled('remember'),
            ]);

            // للتطوير: عرض الكود
            if (config('app.debug')) {
                session()->flash('dev_2fa_code', $code);
            }

            // للإنتاج: أرسل الكود
            // Mail::to($admin->email)->send(new TwoFactorCode($code));

            AdminAuditLog::create([
                'admin_id' => $admin->id,
                'action' => '2fa_sent',
                'description' => '2FA code sent',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.2fa.show');
        }

        // ==================== 9. Complete Login ====================
        return $this->completeLogin($admin, $request->filled('remember'), $request);
    }

    /**
     * إكمال تسجيل الدخول
     */
    private function completeLogin($admin, $remember, $request)
    {
        Auth::guard('admin')->login($admin, $remember);

        // تحديث بيانات آخر دخول
        $admin->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
            'failed_attempts' => 0,
            'locked_until' => null,
        ]);

        // تنظيف Rate Limiter
        RateLimiter::clear('admin-login:' . $request->ip());

        // تحديث سجل المحاولة كـ ناجح
        try {
            \DB::table('login_attempts')
                ->where('email', $request->email)
                ->where('ip_address', $request->ip())
                ->where('successful', false)
                ->latest()
                ->limit(1)
                ->update(['successful' => true]);
        } catch (\Exception $e) {
            // تجاهل
        }

        // Audit Log
        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'login_success',
            'description' => 'Admin logged in successfully',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // تحديث وقت النشاط
        session(['admin_last_activity' => now()]);

        return redirect()->route('admin.dashboard');
    }

    /**
     * عرض صفحة 2FA
     */
    public function show2FA()
    {
        if (!session('2fa_admin_id')) {
            return redirect()->route('admin.login');
        }
        return view('admin.auth.2fa');
    }

    /**
     * التحقق من كود 2FA
     */
    public function verify2FA(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $adminId = session('2fa_admin_id');
        if (!$adminId) {
            return redirect()->route('admin.login');
        }

        $admin = Admin::find($adminId);
        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // التحقق من الكود
        if ($admin->two_factor_code !== $request->code) {
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        if ($admin->two_factor_expires_at && Carbon::parse($admin->two_factor_expires_at)->isPast()) {
            return back()->withErrors(['code' => 'Code expired. Please login again.']);
        }

        // مسح الكود
        $admin->update([
            'two_factor_code' => null,
            'two_factor_expires_at' => null,
        ]);

        // مسح الجلسة المؤقتة
        session()->forget(['2fa_admin_id', '2fa_remember']);

        return $this->completeLogin($admin, false, $request);
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        if ($admin) {
            AdminAuditLog::create([
                'admin_id' => $admin->id,
                'action' => 'logout',
                'description' => 'Admin logged out',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}