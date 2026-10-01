<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    /**
     * عرض الشهادات المتاحة للمستخدم
     */
    public function index()
    {
        $user = Auth::user();
        $certificates = [];
        
        $categories = [
            'general' => [
                'name' => 'General Security Awareness',
                'icon' => '👤',
                'color' => 'from-blue-500 to-blue-700',
            ],
            'it_professional' => [
                'name' => 'IT Professional Security',
                'icon' => '💻',
                'color' => 'from-green-500 to-emerald-700',
            ],
            'security_expert' => [
                'name' => 'Security Expert Advanced',
                'icon' => '🛡️',
                'color' => 'from-purple-500 to-fuchsia-600',
            ],
        ];
        
        foreach ($categories as $type => $cat) {
            $totalScenarios = DB::table('scenarios')
                ->where('user_type', $type)
                ->where('is_active', true)
                ->count();
            
            $completedScenarios = DB::table('user_progress')
                ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                ->where('user_progress.user_id', $user->id)
                ->where('user_progress.status', 'completed')
                ->where('scenarios.user_type', $type)
                ->count();
            
            $certificates[] = [
                'type' => $type,
                'name' => $cat['name'],
                'icon' => $cat['icon'],
                'color' => $cat['color'],
                'total_scenarios' => $totalScenarios,
                'completed_scenarios' => $completedScenarios,
                'is_earned' => ($totalScenarios > 0 && $completedScenarios >= $totalScenarios),
                'progress' => $totalScenarios > 0 ? round(($completedScenarios / $totalScenarios) * 100) : 0,
            ];
        }

        return view('certificates.index', compact('certificates', 'user'));
    }

    /**
     * عرض شهادة محددة
     */
    public function show($type)
    {
        $user = Auth::user();
        
        $categories = [
            'general' => 'General Security Awareness',
            'it_professional' => 'IT Professional Security',
            'security_expert' => 'Security Expert Advanced',
        ];
        
        if (!isset($categories[$type])) {
            return redirect('/certificates');
        }
        
        $totalScenarios = DB::table('scenarios')
            ->where('user_type', $type)
            ->where('is_active', true)
            ->count();
        
        $completedScenarios = DB::table('user_progress')
            ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
            ->where('user_progress.user_id', $user->id)
            ->where('user_progress.status', 'completed')
            ->where('scenarios.user_type', $type)
            ->count();
        
        if ($completedScenarios < $totalScenarios || $totalScenarios == 0) {
            return redirect('/certificates')->with('error', 'Complete all scenarios first!');
        }
        
        $certificateNumber = 'HF-' . date('Y') . '-' . strtoupper(substr(md5($user->id . $type . date('Ymd')), 0, 6));
        $issueDate = date('F d, Y');
        $courseName = $categories[$type];
        
        return view('certificates.show', compact('user', 'type', 'certificateNumber', 'issueDate', 'courseName'));
    }

    /**
     * تحميل الشهادة PDF
     */
    public function downloadPDF($type)
    {
        $user = Auth::user();
        
        $categories = [
            'general' => 'General Security Awareness',
            'it_professional' => 'IT Professional Security',
            'security_expert' => 'Security Expert Advanced',
        ];
        
        if (!isset($categories[$type])) {
            return redirect('/certificates');
        }
        
        $certificateNumber = 'HF-' . date('Y') . '-' . strtoupper(substr(md5($user->id . $type . date('Ymd')), 0, 6));
        $issueDate = date('F d, Y');
        $courseName = $categories[$type];
        
        $pdf = Pdf::loadView('certificates.pdf', compact('user', 'courseName', 'certificateNumber', 'issueDate'));
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf->download('HF-Certificate-' . $certificateNumber . '.pdf');
    }
}