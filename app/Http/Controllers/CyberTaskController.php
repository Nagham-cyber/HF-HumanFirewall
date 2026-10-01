<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CyberTaskController extends Controller
{
    /**
     * عرض قائمة المهام
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $section = $request->get('section', 'all');

        $query = DB::table('cyber_tasks')->where('is_active', true);

        if ($section && $section !== 'all') {
            $query->where('section', $section);
        }

        $tasks = $query->orderBy('reference_code')->get();

        // المهام التي سلّمها المستخدم
        $submittedTaskIds = DB::table('task_submissions')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->pluck('task_id')
            ->toArray();

        // المهام المعتمدة
        $approvedTaskIds = DB::table('task_submissions')
            ->where('user_id', $user->id)
            ->where('admin_approved', true)
            ->pluck('task_id')
            ->toArray();

        // المهام قيد المراجعة
        $pendingTaskIds = DB::table('task_submissions')
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->where('admin_reviewed', false)
            ->pluck('task_id')
            ->toArray();

        // إحصائيات
        $stats = [
            'total' => DB::table('cyber_tasks')->where('is_active', true)->count(),
            'completed' => count($approvedTaskIds),
            'in_progress' => DB::table('task_submissions')
                ->where('user_id', $user->id)
                ->where('status', 'in_progress')
                ->count(),
            'pending' => count($pendingTaskIds),
        ];

        return view('tasks.index', compact(
            'tasks',
            'submittedTaskIds',
            'approvedTaskIds',
            'pendingTaskIds',
            'stats',
            'section'
        ));
    }

    /**
     * عرض تفاصيل مهمة
     */
    public function show($id)
    {
        $user = Auth::user();

        $task = DB::table('cyber_tasks')->find($id);
        if (!$task) {
            return redirect('/tasks')->with('error', 'Task not found');
        }

        $submission = DB::table('task_submissions')
            ->where('user_id', $user->id)
            ->where('task_id', $id)
            ->first();

        return view('tasks.show', compact('task', 'submission'));
    }

    /**
     * حفظ تقرير المهمة (بدون XP - ينتظر موافقة الأدمن)
     */
    public function submit(Request $request, $id)
    {
        $user = Auth::user();

        $task = DB::table('cyber_tasks')->find($id);
        if (!$task) {
            return redirect('/tasks')->with('error', 'Task not found');
        }

        $request->validate([
            'step_by_step' => 'required|string|min:10',
            'observations' => 'required|string|min:10',
            'lessons_learned' => 'required|string|min:10',
            'difficulty_rating' => 'required|integer|min:1|max:5',
            'status' => 'required|in:completed,in_progress',
        ]);

        $existing = DB::table('task_submissions')
            ->where('user_id', $user->id)
            ->where('task_id', $id)
            ->first();

        $data = [
            'step_by_step' => $request->step_by_step,
            'observations' => $request->observations,
            'lessons_learned' => $request->lessons_learned,
            'difficulty_rating' => $request->difficulty_rating,
            'status' => $request->status,
            'submitted_at' => now(),
            'updated_at' => now(),
            // إعادة تعيين حالة المراجعة عند إعادة التسليم
            'admin_reviewed' => false,
            'admin_approved' => false,
            'admin_feedback' => null,
            'reviewed_at' => null,
            'reviewed_by' => null,
        ];

        if ($existing) {
            DB::table('task_submissions')
                ->where('id', $existing->id)
                ->update($data);
        } else {
            $data['user_id'] = $user->id;
            $data['task_id'] = $id;
            $data['created_at'] = now();
            $data['xp_awarded'] = false;
            DB::table('task_submissions')->insert($data);
        }

        $message = 'Task submitted successfully! Your submission is now pending admin review.';
        if ($request->status === 'completed') {
            $message .= ' You will receive +' . $task->points . ' XP once approved.';
        }

        return redirect('/tasks/' . $id)->with('success', $message);
    }
}