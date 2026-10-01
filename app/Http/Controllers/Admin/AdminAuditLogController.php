<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminAuditLogController extends Controller
{
    /**
     * عرض جميع السجلات
     */
    public function index(Request $request)
    {
        $action = $request->get('action');
        $adminId = $request->get('admin_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        $search = $request->get('search');

        $query = DB::table('admin_audit_logs')
            ->leftJoin('admins', 'admin_audit_logs.admin_id', '=', 'admins.id')
            ->select(
                'admin_audit_logs.*',
                'admins.name as admin_name',
                'admins.email as admin_email'
            );

        if ($action && $action !== 'all') {
            $query->where('admin_audit_logs.action', $action);
        }

        if ($adminId && $adminId !== 'all') {
            $query->where('admin_audit_logs.admin_id', $adminId);
        }

        if ($dateFrom) {
            $query->whereDate('admin_audit_logs.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('admin_audit_logs.created_at', '<=', $dateTo);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('admin_audit_logs.action', 'like', "%{$search}%")
                  ->orWhere('admin_audit_logs.description', 'like', "%{$search}%")
                  ->orWhere('admin_audit_logs.ip_address', 'like', "%{$search}%");
            });
        }

        $logs = $query->orderBy('admin_audit_logs.created_at', 'desc')->paginate(30);

        // قائمة الحركات الفريدة
        $uniqueActions = DB::table('admin_audit_logs')
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        // قائمة الأدمن
        $admins = DB::table('admins')->get(['id', 'name', 'email']);

        // إحصائيات
        $stats = [
            'total' => DB::table('admin_audit_logs')->count(),
            'today' => DB::table('admin_audit_logs')->whereDate('created_at', Carbon::today())->count(),
            'this_week' => DB::table('admin_audit_logs')->where('created_at', '>=', Carbon::now()->subDays(7))->count(),
            'unique_actions' => $uniqueActions->count(),
        ];

        // أكثر الحركات تكراراً
        $topActions = DB::table('admin_audit_logs')
            ->select('action', DB::raw('count(*) as count'))
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.audit-logs.index', compact(
            'logs',
            'stats',
            'uniqueActions',
            'admins',
            'topActions',
            'action',
            'adminId',
            'dateFrom',
            'dateTo',
            'search'
        ));
    }

    /**
     * عرض تفاصيل سجل
     */
    public function show($id)
    {
        $log = DB::table('admin_audit_logs')
            ->leftJoin('admins', 'admin_audit_logs.admin_id', '=', 'admins.id')
            ->select(
                'admin_audit_logs.*',
                'admins.name as admin_name',
                'admins.email as admin_email',
                'admins.role as admin_role'
            )
            ->where('admin_audit_logs.id', $id)
            ->first();

        if (!$log) {
            return redirect()->route('admin.audit-logs.index')->with('error', 'Log not found.');
        }

        return view('admin.audit-logs.show', compact('log'));
    }

    /**
     * تصدير السجلات إلى CSV
     */
    public function export(Request $request)
    {
        $action = $request->get('action');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = DB::table('admin_audit_logs')
            ->leftJoin('admins', 'admin_audit_logs.admin_id', '=', 'admins.id')
            ->select(
                'admin_audit_logs.id',
                'admin_audit_logs.action',
                'admin_audit_logs.description',
                'admin_audit_logs.ip_address',
                'admin_audit_logs.user_agent',
                'admin_audit_logs.created_at',
                'admins.name as admin_name',
                'admins.email as admin_email'
            );

        if ($action && $action !== 'all') {
            $query->where('admin_audit_logs.action', $action);
        }

        if ($dateFrom) {
            $query->whereDate('admin_audit_logs.created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('admin_audit_logs.created_at', '<=', $dateTo);
        }

        $logs = $query->orderBy('admin_audit_logs.created_at', 'desc')->get();

        $filename = 'audit-logs-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');

            // UTF-8 BOM
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($file, ['ID', 'Admin', 'Email', 'Action', 'Description', 'IP', 'User Agent', 'Date']);

            // Rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->admin_name ?? 'System',
                    $log->admin_email ?? 'N/A',
                    $log->action,
                    $log->description,
                    $log->ip_address,
                    $log->user_agent,
                    $log->created_at,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}