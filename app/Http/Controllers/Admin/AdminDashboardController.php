<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $weekAgo = Carbon::now()->subDays(7);

        // الإحصائيات الرئيسية
        $stats = [
            'total_users' => DB::table('users')->count(),
            'active_users_today' => DB::table('users')
                ->whereDate('last_login_at', $today)
                ->count() ?: DB::table('users')
                ->whereDate('updated_at', $today)
                ->count(),
            'total_scenarios' => DB::table('scenarios')->count(),
            'total_tasks' => DB::table('cyber_tasks')->count(),
            'total_challenges' => DB::table('daily_challenges')->count(),
            'total_submissions' => DB::table('task_submissions')->count(),
            'completed_submissions' => DB::table('task_submissions')
                ->where('status', 'completed')
                ->count(),
            'in_progress_submissions' => DB::table('task_submissions')
                ->where('status', 'in_progress')
                ->count(),
            'total_mistakes' => DB::table('user_mistakes')->count(),
            'total_xp_awarded' => DB::table('users')->sum('xp_points'),
        ];

        // أسبوعياً
        $weeklyStats = [
            'new_users' => DB::table('users')
                ->where('created_at', '>=', $weekAgo)
                ->count(),
            'new_submissions' => DB::table('task_submissions')
                ->where('created_at', '>=', $weekAgo)
                ->count(),
            'completed_tasks' => DB::table('task_submissions')
                ->where('status', 'completed')
                ->where('created_at', '>=', $weekAgo)
                ->count(),
        ];

        // Top 10 مستخدمين
        $topUsers = DB::table('users')
            ->orderBy('xp_points', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'email', 'xp_points', 'security_rank']);

        // آخر المهام المسلمة
        $recentSubmissions = DB::table('task_submissions')
            ->join('users', 'task_submissions.user_id', '=', 'users.id')
            ->join('cyber_tasks', 'task_submissions.task_id', '=', 'cyber_tasks.id')
            ->select(
                'task_submissions.*',
                'users.name as user_name',
                'users.email as user_email',
                'cyber_tasks.title as task_title',
                'cyber_tasks.reference_code',
                'cyber_tasks.section'
            )
            ->orderBy('task_submissions.created_at', 'desc')
            ->limit(10)
            ->get();

        // آخر المستخدمين المسجلين
        $recentUsers = DB::table('users')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'name', 'email', 'created_at', 'xp_points']);

        // إحصائيات المهام حسب القسم
        $tasksBySection = DB::table('cyber_tasks')
            ->select('section', DB::raw('count(*) as total'))
            ->groupBy('section')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'weeklyStats',
            'topUsers',
            'recentSubmissions',
            'recentUsers',
            'tasksBySection'
        ));
    }
}