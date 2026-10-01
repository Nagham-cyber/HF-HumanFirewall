<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // إحصائيات عامة
        $stats = [
            'total_points' => $user->xp_points ?? 0,
            'completed' => 0,
            'total_scenarios' => 0,
            'correct_answers' => 0,
            'wrong_answers' => 0,
            'streak_days' => $user->streak_days ?? 0,
        ];
        
        $categoryStats = [];
        $recentScenarios = collect([]);
        $earnedBadges = collect([]);
        $awarenessLevels = collect([]);
        
        try {
            // الإجماليات
            $stats['total_scenarios'] = DB::table('scenarios')->where('is_active', true)->count();
            $stats['completed'] = DB::table('user_progress')
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count();
            
            $stats['wrong_answers'] = DB::table('user_mistakes')
                ->where('user_id', $user->id)
                ->count();
            
            $stats['correct_answers'] = $stats['completed'];
            
            // إحصائيات الفئات
            $categories = ['general', 'it_professional', 'security_expert'];
            $levels = ['easy', 'medium', 'hard', 'expert'];
            
            foreach ($categories as $category) {
                foreach ($levels as $level) {
                    $total = DB::table('scenarios')
                        ->where('user_type', $category)
                        ->where('level', $level)
                        ->where('is_active', true)
                        ->count();
                    
                    $completed = DB::table('user_progress')
                        ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                        ->where('user_progress.user_id', $user->id)
                        ->where('user_progress.status', 'completed')
                        ->where('scenarios.user_type', $category)
                        ->where('scenarios.level', $level)
                        ->count();
                    
                    $categoryStats[$category][$level] = [
                        'completed' => $completed,
                        'total' => $total,
                        'percentage' => $total > 0 ? round(($completed / $total) * 100) : 0,
                    ];
                }
            }
            
            // آخر السيناريوهات المكتملة
            $recentScenarios = DB::table('user_progress')
                ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                ->where('user_progress.user_id', $user->id)
                ->where('user_progress.status', 'completed')
                ->select('scenarios.title', 'scenarios.level', 'scenarios.user_type', 'user_progress.completed_at')
                ->orderBy('user_progress.completed_at', 'desc')
                ->limit(10)
                ->get();
            
            // الشارات المكتسبة
            $earnedBadges = DB::table('user_badges')
                ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
                ->where('user_badges.user_id', $user->id)
                ->select('badges.name', 'badges.icon', 'badges.color', 'user_badges.earned_at')
                ->orderBy('user_badges.earned_at', 'desc')
                ->get();
            
            // مستويات الوعي
            $awarenessLevels = DB::table('user_awareness_levels')
                ->where('user_id', $user->id)
                ->get();
            
        } catch (\Exception $e) {}

        return view('progress.index', compact('stats', 'categoryStats', 'recentScenarios', 'earnedBadges', 'awarenessLevels'));
    }
}