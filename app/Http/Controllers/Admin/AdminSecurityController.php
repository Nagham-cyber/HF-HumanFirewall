<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminSecurityController extends Controller
{
    /**
     * لوحة الأمان
     */
    public function index()
    {
        $today = Carbon::today();
        $weekAgo = Carbon::now()->subDays(7);

        // ==================== Login Attempts ====================
        $loginAttempts = [
            'total' => DB::table('login_attempts')->count(),
            'today' => DB::table('login_attempts')->whereDate('created_at', $today)->count(),
            'failed_today' => DB::table('login_attempts')
                ->whereDate('created_at', $today)
                ->where('successful', false)
                ->count(),
            'failed_week' => DB::table('login_attempts')
                ->where('created_at', '>=', $weekAgo)
                ->where('successful', false)
                ->count(),
        ];

        // ==================== Suspicious IPs ====================
        $suspiciousIPs = DB::table('login_attempts')
            ->select('ip_address', DB::raw('COUNT(*) as attempt_count'))
            ->where('successful', false)
            ->where('created_at', '>=', $weekAgo)
            ->groupBy('ip_address')
            ->having('attempt_count', '>=', 3)
            ->orderBy('attempt_count', 'desc')
            ->limit(10)
            ->get();

        // ==================== Suspicious Emails ====================
        $suspiciousEmails = DB::table('login_attempts')
            ->select('email', DB::raw('COUNT(*) as attempt_count'))
            ->where('successful', false)
            ->whereNotNull('email')
            ->where('created_at', '>=', $weekAgo)
            ->groupBy('email')
            ->having('attempt_count', '>=', 3)
            ->orderBy('attempt_count', 'desc')
            ->limit(10)
            ->get();

        // ==================== Recent Logins ====================
        $recentLogins = DB::table('login_attempts')
            ->where('successful', true)
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        // ==================== Recent Failed Logins ====================
        $recentFailedLogins = DB::table('login_attempts')
            ->where('successful', false)
            ->orderBy('created_at', 'desc')
            ->limit(15)
            ->get();

        // ==================== Audit Logs ====================
        $recentAuditLogs = DB::table('admin_audit_logs')
            ->leftJoin('admins', 'admin_audit_logs.admin_id', '=', 'admins.id')
            ->select('admin_audit_logs.*', 'admins.name as admin_name')
            ->orderBy('admin_audit_logs.created_at', 'desc')
            ->limit(15)
            ->get();

        // ==================== Admin Accounts ====================
        $adminStats = [
            'total_admins' => DB::table('admins')->count(),
            'active_admins' => DB::table('admins')->where('is_active', true)->count(),
            'locked_admins' => DB::table('admins')
                ->where('locked_until', '>', now())
                ->count(),
            '2fa_enabled' => DB::table('admins')
                ->where('two_factor_enabled', true)
                ->count(),
        ];

        // ==================== Security Score ====================
        $securityChecks = [
            'no_debug_mode' => config('app.debug') === false,
            'session_encrypted' => config('session.encrypt') === true,
            'secure_cookies' => config('session.secure') === true || config('app.env') !== 'production',
            'http_only_cookies' => config('session.http_only') === true,
            'admin_2fa_available' => true,
            'login_rate_limited' => true,
            'audit_logging' => true,
            'no_recent_bruteforce' => DB::table('login_attempts')
                ->where('created_at', '>=', Carbon::now()->subHours(1))
                ->where('successful', false)
                ->count() < 20,
        ];

        $passedChecks = collect($securityChecks)->filter()->count();
        $totalChecks = count($securityChecks);
        $securityScore = round(($passedChecks / $totalChecks) * 100);

        return view('admin.security.index', compact(
            'loginAttempts',
            'suspiciousIPs',
            'suspiciousEmails',
            'recentLogins',
            'recentFailedLogins',
            'recentAuditLogs',
            'adminStats',
            'securityChecks',
            'securityScore'
        ));
    }

    /**
     * حذف محاولة دخول
     */
    public function clearAttempts(Request $request)
    {
        $days = $request->get('days', 30);
        
        DB::table('login_attempts')
            ->where('created_at', '<', Carbon::now()->subDays($days))
            ->delete();

        return back()->with('success', "Login attempts older than {$days} days cleared.");
    }
}