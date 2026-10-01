<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\AdminAuditLog;

class AdminSettingsController extends Controller
{
    /**
     * عرض صفحة الإعدادات
     */
    public function index()
    {
        $admin = Auth::guard('admin')->user();

        // آخر 10 حركات
        $recentLogs = DB::table('admin_audit_logs')
            ->where('admin_id', $admin->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.settings.index', compact('admin', 'recentLogs'));
    }

    /**
     * تحديث المعلومات الشخصية
     */
    public function updateProfile(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email,' . $admin->id,
        ]);

        $oldData = ['name' => $admin->name, 'email' => $admin->email];

        DB::table('admins')
            ->where('id', $admin->id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => now(),
            ]);

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'profile_updated',
            'description' => 'Admin updated profile information',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => [
                'old' => $oldData,
                'new' => ['name' => $request->name, 'email' => $request->email],
            ],
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * تغيير كلمة المرور
     */
    public function updatePassword(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:10|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#]).+$/',
        ], [
            'new_password.regex' => 'Password must contain: uppercase, lowercase, number, and special character.',
        ]);

        // تحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        DB::table('admins')
            ->where('id', $admin->id)
            ->update([
                'password' => Hash::make($request->new_password),
                'updated_at' => now(),
            ]);

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'password_changed',
            'description' => 'Admin changed password',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * تفعيل/تعطيل 2FA
     */
    public function toggle2FA(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $newState = !$admin->two_factor_enabled;

        DB::table('admins')
            ->where('id', $admin->id)
            ->update([
                'two_factor_enabled' => $newState,
                'updated_at' => now(),
            ]);

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => $newState ? '2fa_enabled' : '2fa_disabled',
            'description' => 'Admin ' . ($newState ? 'enabled' : 'disabled') . ' 2FA',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', '2FA ' . ($newState ? 'enabled' : 'disabled') . ' successfully!');
    }

    /**
     * تحديث قائمة IPs المسموح بها
     */
    public function updateAllowedIPs(Request $request)
    {
        $admin = Auth::guard('admin')->user();

        $request->validate([
            'allowed_ips' => 'nullable|string',
        ]);

        // تحويل النص إلى مصفوفة
        $ips = [];
        if ($request->allowed_ips) {
            $lines = explode("\n", $request->allowed_ips);
            foreach ($lines as $line) {
                $ip = trim($line);
                if ($ip && filter_var($ip, FILTER_VALIDATE_IP)) {
                    $ips[] = $ip;
                }
            }
        }

        DB::table('admins')
            ->where('id', $admin->id)
            ->update([
                'allowed_ips' => empty($ips) ? null : json_encode($ips),
                'updated_at' => now(),
            ]);

        AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => 'allowed_ips_updated',
            'description' => 'Admin updated allowed IPs list',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'metadata' => ['ips' => $ips],
        ]);

        return back()->with('success', 'Allowed IPs updated successfully!');
    }
}
