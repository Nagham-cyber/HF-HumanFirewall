<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserController extends Controller
{
    /**
     * قائمة المستخدمين
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $sort = $request->get('sort', 'xp_points');
        $order = $request->get('order', 'desc');

        $query = DB::table('users');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status && $status !== 'all') {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'banned') {
                $query->where('is_active', false);
            }
        }

        // الترتيب
        $allowedSorts = ['xp_points', 'created_at', 'name', 'streak_days'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'xp_points';
        }
        $order = in_array($order, ['asc', 'desc']) ? $order : 'desc';

        $users = $query->select(
                'id', 'name', 'email', 'xp_points', 'security_rank',
                'streak_days', 'is_active', 'created_at', 'last_login_at'
            )
            ->orderBy($sort, $order)
            ->paginate(20);

        // إحصائيات
        $stats = [
            'total' => DB::table('users')->count(),
            'active' => DB::table('users')->where('is_active', true)->count(),
            'banned' => DB::table('users')->where('is_active', false)->count(),
            'new_today' => DB::table('users')->whereDate('created_at', Carbon::today())->count(),
            'new_this_week' => DB::table('users')->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'total_xp' => DB::table('users')->sum('xp_points'),
        ];

        return view('admin.users.index', compact('users', 'stats', 'search', 'status', 'sort', 'order'));
    }

    /**
     * عرض تفاصيل مستخدم
     */
    public function show($id)
    {
        $user = DB::table('users')
            ->select(
                'id', 'name', 'email', 'xp_points', 'security_rank',
                'streak_days', 'is_active', 'created_at', 'last_login_at',
                'language_preference', 'job_title', 'avatar_url'
            )
            ->where('id', $id)
            ->first();
        if (!$user) {
            return redirect()->route('admin.users')->with('error', 'User not found.');
        }

        // إحصائيات المستخدم
        $stats = [
            'xp' => $user->xp_points,
            'rank' => $user->security_rank ?? 'Novice',
            'streak' => $user->streak_days ?? 0,
            'completed_scenarios' => DB::table('user_progress')
                ->where('user_id', $id)
                ->where('completed', true)
                ->count(),
            'completed_tasks' => DB::table('task_submissions')
                ->where('user_id', $id)
                ->where('status', 'completed')
                ->count(),
            'in_progress_tasks' => DB::table('task_submissions')
                ->where('user_id', $id)
                ->where('status', 'in_progress')
                ->count(),
            'total_mistakes' => DB::table('user_mistakes')
                ->where('user_id', $id)
                ->count(),
            'reviewed_mistakes' => DB::table('user_mistakes')
                ->where('user_id', $id)
                ->where('is_reviewed', true)
                ->count(),
        ];

        // آخر المهام المسلمة
        $recentSubmissions = DB::table('task_submissions')
            ->join('cyber_tasks', 'task_submissions.task_id', '=', 'cyber_tasks.id')
            ->select(
                'task_submissions.*',
                'cyber_tasks.title as task_title',
                'cyber_tasks.reference_code',
                'cyber_tasks.section',
                'cyber_tasks.points as task_points'
            )
            ->where('task_submissions.user_id', $id)
            ->orderBy('task_submissions.created_at', 'desc')
            ->limit(10)
            ->get();

        // آخر الأخطاء
        $recentMistakes = DB::table('user_mistakes')
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'recentSubmissions', 'recentMistakes'));
    }

    /**
     * Ban / Unban
     */
    public function toggleBan($id)
    {
        $user = DB::table('users')->find($id);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        DB::table('users')
            ->where('id', $id)
            ->update([
                'is_active' => !$user->is_active,
                'updated_at' => now(),
            ]);

        $action = $user->is_active ? 'banned' : 'unbanned';
        return back()->with('success', "User {$action} successfully!");
    }

    /**
     * حذف مستخدم
     */
    public function destroy($id)
    {
        $user = DB::table('users')->find($id);
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        DB::table('users')->where('id', $id)->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    /**
     * إعادة تعيين كلمة المرور
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'password' => Hash::make($request->password),
                'updated_at' => now(),
            ]);

        return back()->with('success', 'Password reset successfully!');
    }

    /**
     * تعديل بيانات المستخدم
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        DB::table('users')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'updated_at' => now(),
            ]);

        return back()->with('success', 'User updated successfully!');
    }
}