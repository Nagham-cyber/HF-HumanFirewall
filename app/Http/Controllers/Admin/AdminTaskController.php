<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminTaskController extends Controller
{
    /**
     * الفئات المتاحة (من قاعدة البيانات)
     */
    private function getSections()
    {
        $categories = DB::table('categories')
            ->whereIn('type', ['task', 'both'])
            ->where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        if ($categories->isEmpty()) {
            return [
                'network_defense' => 'Network Defense',
                'dfir' => 'DFIR',
                'pentest' => 'Pentest',
                'devsecops' => 'DevSecOps',
                'grc' => 'GRC',
                'automation' => 'Automation',
            ];
        }

        $sections = [];
        foreach ($categories as $cat) {
            $sections[$cat->slug] = $cat->name;
        }
        return $sections;
    }

    /**
     * توليد reference code تلقائياً
     */
    private function generateReferenceCode($section)
    {
        $prefixes = [
            'network_defense' => 'NET-DEF',
            'dfir' => 'DFIR',
            'pentest' => 'PT',
            'devsecops' => 'DEVSEC',
            'grc' => 'GRC',
            'automation' => 'AUTO',
        ];

        $prefix = $prefixes[$section] ?? 'TASK';

        $lastTask = DB::table('cyber_tasks')
            ->where('reference_code', 'like', $prefix . '-%')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastTask) {
            $parts = explode('-', $lastTask->reference_code);
            $nextNumber = (int) end($parts) + 1;
        }

        return $prefix . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * عرض جميع المهام
     */
    public function index(Request $request)
    {
        $section = $request->get('section');
        $search = $request->get('search');

        $query = DB::table('cyber_tasks');

        if ($section && $section !== 'all') {
            $query->where('section', $section);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference_code', 'like', "%{$search}%");
            });
        }

        $tasks = $query->orderBy('reference_code')->paginate(30);

        $stats = [
            'total' => DB::table('cyber_tasks')->count(),
            'network_defense' => DB::table('cyber_tasks')->where('section', 'network_defense')->count(),
            'dfir' => DB::table('cyber_tasks')->where('section', 'dfir')->count(),
            'pentest' => DB::table('cyber_tasks')->where('section', 'pentest')->count(),
            'devsecops' => DB::table('cyber_tasks')->where('section', 'devsecops')->count(),
            'grc' => DB::table('cyber_tasks')->where('section', 'grc')->count(),
            'automation' => DB::table('cyber_tasks')->where('section', 'automation')->count(),
        ];

        return view('admin.tasks.index', compact('tasks', 'stats', 'section', 'search'));
    }

    /**
     * عرض إجابات الفريق
     */
    public function submissions(Request $request)
    {
        $status = $request->get('status');
        $search = $request->get('search');
        $section = $request->get('section');

        $query = DB::table('task_submissions')
            ->join('users', 'task_submissions.user_id', '=', 'users.id')
            ->join('cyber_tasks', 'task_submissions.task_id', '=', 'cyber_tasks.id')
            ->select(
                'task_submissions.*',
                'users.name as user_name',
                'users.email as user_email',
                'users.xp_points as user_xp',
                'cyber_tasks.title as task_title',
                'cyber_tasks.reference_code',
                'cyber_tasks.section',
                'cyber_tasks.difficulty',
                'cyber_tasks.points as task_points'
            );

        if ($status && $status !== 'all') {
            $query->where('task_submissions.status', $status);
        }

        if ($section && $section !== 'all') {
            $query->where('cyber_tasks.section', $section);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('users.email', 'like', "%{$search}%")
                  ->orWhere('cyber_tasks.title', 'like', "%{$search}%")
                  ->orWhere('cyber_tasks.reference_code', 'like', "%{$search}%");
            });
        }

        $submissions = $query->orderBy('task_submissions.created_at', 'desc')->paginate(20);

        $stats = [
            'total' => DB::table('task_submissions')->count(),
            'completed' => DB::table('task_submissions')->where('status', 'completed')->count(),
            'in_progress' => DB::table('task_submissions')->where('status', 'in_progress')->count(),
            'pending_review' => DB::table('task_submissions')->where('admin_reviewed', false)->where('status', 'completed')->count(),
            'unique_users' => DB::table('task_submissions')->distinct('user_id')->count('user_id'),
        ];

        return view('admin.tasks.submissions', compact('submissions', 'stats', 'status', 'search', 'section'));
    }

    /**
     * عرض تفاصيل إجابة
     */
    public function showSubmission($id)
    {
        $submission = DB::table('task_submissions')
            ->join('users', 'task_submissions.user_id', '=', 'users.id')
            ->join('cyber_tasks', 'task_submissions.task_id', '=', 'cyber_tasks.id')
            ->select(
                'task_submissions.*',
                'task_submissions.id as submission_id',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                'users.xp_points as user_xp',
                'users.security_rank as user_rank',
                'cyber_tasks.id as task_id',
                'cyber_tasks.title as title',
                'cyber_tasks.description',
                'cyber_tasks.section',
                'cyber_tasks.difficulty',
                'cyber_tasks.estimated_time',
                'cyber_tasks.points as task_points',
                'cyber_tasks.reference_code'
            )
            ->where('task_submissions.id', $id)
            ->first();

        if (!$submission) {
            return redirect()->route('admin.tasks.submissions')->with('error', 'Submission not found.');
        }

        $userStats = [
            'total_submissions' => DB::table('task_submissions')->where('user_id', $submission->user_id)->count(),
            'completed' => DB::table('task_submissions')->where('user_id', $submission->user_id)->where('status', 'completed')->count(),
            'xp' => $submission->user_xp,
        ];

        return view('admin.tasks.submission-show', compact('submission', 'userStats'));
    }

    /**
     * مراجعة إجابة (قبول/رفض) — فقط مرة واحدة
     */
    public function reviewSubmission(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'feedback' => 'nullable|string|max:2000',
        ]);

        $submission = DB::table('task_submissions')->find($id);
        if (!$submission) {
            return back()->with('error', 'Submission not found.');
        }

        $admin = Auth::guard('admin')->user();
        $task = DB::table('cyber_tasks')->find($submission->task_id);

        if (!$task) {
            return back()->with('error', 'Task not found.');
        }

        $isApproved = $request->action === 'approve';

        // إذا كان قبول + لم يُمنح XP سابقاً → امنح XP
        if ($isApproved && !$submission->xp_awarded) {
            DB::table('users')
                ->where('id', $submission->user_id)
                ->increment('xp_points', $task->points);
        }

        DB::table('task_submissions')
            ->where('id', $id)
            ->update([
                'admin_reviewed' => true,
                'admin_approved' => $isApproved,
                'admin_feedback' => $request->feedback,
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
                'xp_awarded' => $isApproved ? true : $submission->xp_awarded,
                'updated_at' => now(),
            ]);

        \App\Models\AdminAuditLog::create([
            'admin_id' => $admin->id,
            'action' => $isApproved ? 'submission_approved' : 'submission_rejected',
            'description' => ($isApproved ? 'Approved' : 'Rejected') . " submission #{$id}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        $message = $isApproved
            ? "Submission approved! +{$task->points} XP awarded to user."
            : "Submission rejected. Feedback sent to user.";

        return back()->with('success', $message);
    }

    /**
     * صفحة إضافة مهمة جديدة
     */
    public function create()
    {
        $sections = $this->getSections();
        return view('admin.tasks.create', compact('sections'));
    }

    /**
     * توليد reference code عبر AJAX
     */
    public function generateCode(Request $request)
    {
        $section = $request->get('section');
        $code = $this->generateReferenceCode($section);
        return response()->json(['code' => $code]);
    }

    /**
     * حفظ مهمة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'reference_code' => 'required|unique:cyber_tasks,reference_code',
            'section' => 'required|string',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'overview' => 'nullable|string',
            'objectives' => 'nullable|array',
            'instructions' => 'nullable|array',
            'tools_list' => 'nullable|array',
            'resources' => 'nullable|array',
            'deliverables' => 'nullable|array',
            'hints' => 'nullable|string',
            'estimated_time' => 'required|string',
            'difficulty' => 'required|integer|min:1|max:5',
            'points' => 'required|integer|min:10',
        ]);

        $objectives = array_values(array_filter($request->objectives ?? []));
        $instructions = array_values(array_filter($request->instructions ?? []));
        $tools_list = array_values(array_filter($request->tools_list ?? []));
        $resources = array_values(array_filter($request->resources ?? []));
        $deliverables = array_values(array_filter($request->deliverables ?? []));

        DB::table('cyber_tasks')->insert([
            'reference_code' => $request->reference_code,
            'section' => $request->section,
            'title' => $request->title,
            'description' => $request->description,
            'overview' => $request->overview,
            'objectives' => json_encode($objectives),
            'instructions' => json_encode($instructions),
            'tools_list' => json_encode($tools_list),
            'resources' => json_encode($resources),
            'deliverables' => json_encode($deliverables),
            'hints' => $request->hints,
            'estimated_time' => $request->estimated_time,
            'difficulty' => $request->difficulty,
            'points' => $request->points,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully!');
    }

    /**
     * حذف مهمة
     */
    public function destroy($id)
    {
        DB::table('cyber_tasks')->where('id', $id)->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully!');
    }
}