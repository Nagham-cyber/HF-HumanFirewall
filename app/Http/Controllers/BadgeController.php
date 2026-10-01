<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $badges = collect([]);
        $earnedBadgeIds = collect([]);
        $categoryProgress = [];
        
        try {
            $badges = DB::table('badges')->where('is_active', true)->get();
            
            $earnedBadgeIds = DB::table('user_badges')
                ->where('user_id', $user->id)
                ->pluck('badge_id');
            
            // حساب تقدم كل فئة ومستوى
            $categories = ['general', 'it_professional', 'security_expert'];
            $levels = ['easy', 'medium', 'hard', 'expert'];
            
            foreach ($categories as $category) {
                foreach ($levels as $level) {
                    $totalInLevel = DB::table('scenarios')
                        ->where('user_type', $category)
                        ->where('level', $level)
                        ->where('is_active', true)
                        ->count();
                    
                    $completedInLevel = DB::table('user_progress')
                        ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                        ->where('user_progress.user_id', $user->id)
                        ->where('user_progress.status', 'completed')
                        ->where('scenarios.user_type', $category)
                        ->where('scenarios.level', $level)
                        ->count();
                    
                    $categoryProgress[$category][$level] = [
                        'completed' => $completedInLevel,
                        'total' => $totalInLevel,
                        'percentage' => $totalInLevel > 0 ? round(($completedInLevel / $totalInLevel) * 100) : 0,
                    ];
                }
            }
            
        } catch (\Exception $e) {}
        
        return view('badges.index', compact('badges', 'earnedBadgeIds', 'categoryProgress'));
    }
}