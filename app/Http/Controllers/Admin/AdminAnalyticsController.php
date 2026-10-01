<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAnalyticsController extends Controller
{
    public function index()
    {
        // ==================== User Growth ====================
        $userGrowth = $this->getUserGrowthLast30Days();

        // ==================== Submissions Trend ====================
        $submissionsTrend = $this->getSubmissionsTrendLast30Days();

        // ==================== Tasks by Section ====================
        $tasksBySection = DB::table('cyber_tasks')
            ->select('section', DB::raw('count(*) as total'))
            ->groupBy('section')
            ->orderBy('total', 'desc')
            ->get();

        // ==================== Scenarios by User Type ====================
        $scenariosByType = DB::table('scenarios')
            ->select('user_type', DB::raw('count(*) as total'))
            ->groupBy('user_type')
            ->get();

        // ==================== Submissions by Status ====================
        $submissionsByStatus = DB::table('task_submissions')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // ==================== Top Performers ====================
        $topPerformers = DB::table('users')
            ->select(
                'id',
                'name',
                'email',
                'xp_points',
                'security_rank',
                'streak_days',
                DB::raw('(SELECT COUNT(*) FROM task_submissions WHERE task_submissions.user_id = users.id AND task_submissions.status = "completed") as completed_tasks'),
                DB::raw('(SELECT COUNT(*) FROM user_mistakes WHERE user_mistakes.user_id = users.id) as total_mistakes')
            )
            ->orderBy('xp_points', 'desc')
            ->limit(10)
            ->get();

        // ==================== Weak Areas ====================
        $weakAreas = DB::table('user_mistakes')
            ->select('category', DB::raw('count(*) as total'))
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        // ==================== Task Difficulty Stats ====================
        $taskDifficultyStats = DB::table('task_submissions')
            ->select('difficulty_rating', DB::raw('count(*) as total'))
            ->whereNotNull('difficulty_rating')
            ->groupBy('difficulty_rating')
            ->orderBy('difficulty_rating')
            ->get();

        // ==================== Overview Stats ====================
        $stats = [
            'total_users' => DB::table('users')->count(),
            'new_users_week' => DB::table('users')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'total_submissions' => DB::table('task_submissions')->count(),
            'completed_submissions' => DB::table('task_submissions')
                ->where('status', 'completed')
                ->count(),
            'approved_submissions' => DB::table('task_submissions')
                ->where('admin_approved', true)
                ->count(),
            'pending_review' => DB::table('task_submissions')
                ->where('admin_reviewed', false)
                ->where('status', 'completed')
                ->count(),
            'total_xp_awarded' => DB::table('users')->sum('xp_points'),
            'avg_xp_per_user' => round(DB::table('users')->avg('xp_points') ?? 0),
        ];

        // ==================== Weekly Comparison ====================
        $thisWeek = [
            'users' => DB::table('users')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'submissions' => DB::table('task_submissions')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'completed' => DB::table('task_submissions')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->where('status', 'completed')
                ->count(),
        ];

        $lastWeek = [
            'users' => DB::table('users')
                ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
                ->count(),
            'submissions' => DB::table('task_submissions')
                ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
                ->count(),
            'completed' => DB::table('task_submissions')
                ->whereBetween('created_at', [Carbon::now()->subDays(14), Carbon::now()->subDays(7)])
                ->where('status', 'completed')
                ->count(),
        ];

        return view('admin.analytics.index', compact(
            'userGrowth',
            'submissionsTrend',
            'tasksBySection',
            'scenariosByType',
            'submissionsByStatus',
            'topPerformers',
            'weakAreas',
            'taskDifficultyStats',
            'stats',
            'thisWeek',
            'lastWeek'
        ));
    }

    /**
     * نمو المستخدمين خلال 30 يوم
     */
    private function getUserGrowthLast30Days()
    {
        $days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = DB::table('users')
                ->whereDate('created_at', $date)
                ->count();
            $days[] = [
                'date' => Carbon::now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }
        return $days;
    }

    /**
     * اتجاه التسليمات خلال 30 يوم
     */
    private function getSubmissionsTrendLast30Days()
    {
        $days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = DB::table('task_submissions')
                ->whereDate('created_at', $date)
                ->count();
            $days[] = [
                'date' => Carbon::now()->subDays($i)->format('M d'),
                'count' => $count,
            ];
        }
        return $days;
    }
}