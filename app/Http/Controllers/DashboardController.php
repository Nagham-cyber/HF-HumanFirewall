<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * لوحة التحكم الرئيسية
     */
    public function index()
    {
        $user = Auth::user();

        // إحصائيات المستخدم
        $stats = [
            'total_points' => $user->xp_points ?? 0,
            'security_level' => $user->security_rank ?? 'Security Novice',
            'streak_days' => $user->streak_days ?? 0,
        ];

        // إحصائيات الفئات
        $categoryStats = [
            [
                'type' => 'general',
                'label' => 'General Users',
                'total' => DB::table('scenarios')->where('user_type', 'general')->count(),
                'completed' => DB::table('user_progress')
                    ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                    ->where('user_progress.user_id', $user->id)
                    ->where('user_progress.status', 'completed')
                    ->where('scenarios.user_type', 'general')
                    ->count(),
            ],
            [
                'type' => 'it_professional',
                'label' => 'IT Professionals',
                'total' => DB::table('scenarios')->where('user_type', 'it_professional')->count(),
                'completed' => DB::table('user_progress')
                    ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                    ->where('user_progress.user_id', $user->id)
                    ->where('user_progress.status', 'completed')
                    ->where('scenarios.user_type', 'it_professional')
                    ->count(),
            ],
            [
                'type' => 'security_expert',
                'label' => 'Security Experts',
                'total' => DB::table('scenarios')->where('user_type', 'security_expert')->count(),
                'completed' => DB::table('user_progress')
                    ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                    ->where('user_progress.user_id', $user->id)
                    ->where('user_progress.status', 'completed')
                    ->where('scenarios.user_type', 'security_expert')
                    ->count(),
            ],
        ];

        // إضافة النسبة المئوية
        foreach ($categoryStats as &$cat) {
            $cat['percentage'] = $cat['total'] > 0 ? round(($cat['completed'] / $cat['total']) * 100) : 0;
        }

        // Top 5 متصدرين
        $topUsers = DB::table('users')
            ->select('id', 'name', 'xp_points', 'security_rank')
            ->orderBy('xp_points', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'categoryStats', 'topUsers'));
    }

    /**
     * المتصدرون (Leaderboard)
     */
    public function leaderboard()
    {
        $topUsers = DB::table('users')
            ->select('id', 'name', 'xp_points', 'security_rank', 'streak_days')
            ->orderBy('xp_points', 'desc')
            ->limit(100)
            ->get();

        return view('leaderboard', compact('topUsers'));
    }
}