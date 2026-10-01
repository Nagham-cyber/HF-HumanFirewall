<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminReportController extends Controller
{
    /**
     * الصفحة الرئيسية للتقارير
     */
    public function index()
    {
        $stats = [
            'total_users' => DB::table('users')->count(),
            'total_scenarios' => DB::table('scenarios')->count(),
            'total_tasks' => DB::table('cyber_tasks')->count(),
            'total_submissions' => DB::table('task_submissions')->count(),
            'total_mistakes' => DB::table('user_mistakes')->count(),
            'total_xp' => DB::table('users')->sum('xp_points'),
        ];

        // أكثر السيناريوهات فشلاً
        $failedScenarios = DB::table('user_mistakes')
            ->select('scenario_id', DB::raw('count(*) as fail_count'))
            ->whereNotNull('scenario_id')
            ->groupBy('scenario_id')
            ->orderBy('fail_count', 'desc')
            ->limit(10)
            ->get();

        foreach ($failedScenarios as $fail) {
            $scenario = DB::table('scenarios')->find($fail->scenario_id);
            $fail->title = $scenario ? $scenario->title : 'Unknown';
        }

        // أكثر المهام إكمالاً
        $topTasks = DB::table('task_submissions')
            ->join('cyber_tasks', 'task_submissions.task_id', '=', 'cyber_tasks.id')
            ->select('cyber_tasks.title', 'cyber_tasks.reference_code', DB::raw('count(*) as count'))
            ->where('task_submissions.status', 'completed')
            ->groupBy('cyber_tasks.id', 'cyber_tasks.title', 'cyber_tasks.reference_code')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // المستخدمون الأكثر نشاطاً
        $topUsers = DB::table('users')
            ->orderBy('xp_points', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'email', 'xp_points', 'security_rank', 'streak_days']);

        return view('admin.reports.index', compact('stats', 'failedScenarios', 'topTasks', 'topUsers'));
    }

    // ==================== USERS REPORT ====================

    public function exportUsers(Request $request)
    {
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = DB::table('users');

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $users = $query->orderBy('created_at', 'desc')->get();

        $filename = 'users-report-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID', 'Name', 'Email', 'XP', 'Rank', 'Streak Days',
                'Status', 'Joined At', 'Completed Tasks', 'Total Mistakes'
            ]);

            foreach ($users as $user) {
                $completed = DB::table('task_submissions')
                    ->where('user_id', $user->id)
                    ->where('status', 'completed')
                    ->count();

                $mistakes = DB::table('user_mistakes')->where('user_id', $user->id)->count();

                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->xp_points ?? 0,
                    $user->security_rank ?? 'Novice',
                    $user->streak_days ?? 0,
                    ($user->is_active ?? true) ? 'Active' : 'Banned',
                    $user->created_at,
                    $completed,
                    $mistakes,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== SUBMISSIONS REPORT ====================

    public function exportSubmissions(Request $request)
    {
        $status = $request->get('status');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = DB::table('task_submissions')
            ->join('users', 'task_submissions.user_id', '=', 'users.id')
            ->join('cyber_tasks', 'task_submissions.task_id', '=', 'cyber_tasks.id')
            ->select(
                'task_submissions.id',
                'users.name as user_name',
                'users.email as user_email',
                'cyber_tasks.title as task_title',
                'cyber_tasks.reference_code',
                'cyber_tasks.section',
                'cyber_tasks.difficulty',
                'task_submissions.status',
                'task_submissions.difficulty_rating',
                'task_submissions.submitted_at',
                'task_submissions.created_at'
            );

        if ($status && $status !== 'all') {
            $query->where('task_submissions.status', $status);
        }

        if ($dateFrom) {
            $query->whereDate('task_submissions.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('task_submissions.created_at', '<=', $dateTo);
        }

        $submissions = $query->orderBy('task_submissions.created_at', 'desc')->get();

        $filename = 'submissions-report-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($submissions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID', 'User', 'Email', 'Task Title', 'Reference', 'Section',
                'Difficulty', 'Status', 'Rating', 'Submitted At'
            ]);

            foreach ($submissions as $sub) {
                fputcsv($file, [
                    $sub->id,
                    $sub->user_name,
                    $sub->user_email,
                    $sub->task_title,
                    $sub->reference_code,
                    $sub->section,
                    $sub->difficulty,
                    $sub->status,
                    $sub->difficulty_rating ?? 'N/A',
                    $sub->submitted_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== SCENARIOS REPORT ====================

    public function exportScenarios(Request $request)
    {
        $scenarios = DB::table('scenarios')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'scenarios-report-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($scenarios) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID', 'Title', 'Category', 'User Type', 'Level', 'Minutes', 'Created At'
            ]);

            foreach ($scenarios as $s) {
                fputcsv($file, [
                    $s->id,
                    $s->title,
                    $s->category,
                    $s->user_type,
                    $s->level,
                    $s->estimated_minutes,
                    $s->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== MISTAKES REPORT ====================

    public function exportMistakes(Request $request)
    {
        $mistakes = DB::table('user_mistakes')
            ->join('users', 'user_mistakes.user_id', '=', 'users.id')
            ->select(
                'user_mistakes.id',
                'users.name as user_name',
                'users.email as user_email',
                'user_mistakes.category',
                'user_mistakes.question',
                'user_mistakes.user_answer',
                'user_mistakes.correct_answer',
                'user_mistakes.is_reviewed',
                'user_mistakes.created_at'
            )
            ->orderBy('user_mistakes.created_at', 'desc')
            ->get();

        $filename = 'mistakes-report-' . now()->format('Y-m-d-His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($mistakes) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'ID', 'User', 'Email', 'Category', 'Question',
                'User Answer', 'Correct Answer', 'Reviewed', 'Date'
            ]);

            foreach ($mistakes as $m) {
                fputcsv($file, [
                    $m->id,
                    $m->user_name,
                    $m->user_email,
                    $m->category,
                    $m->question,
                    $m->user_answer,
                    $m->correct_answer,
                    $m->is_reviewed ? 'Yes' : 'No',
                    $m->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}