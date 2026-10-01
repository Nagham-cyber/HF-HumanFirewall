<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ScenarioController extends Controller
{
    /**
     * عرض قائمة السيناريوهات حسب الفئة
     */
    public function index(Request $request)
    {
        $userType = $request->query('type', 'general');
        $scenarios = collect([]);
        $completedIds = collect([]);
        
        try {
            $scenarios = DB::table('scenarios')
                ->where('is_active', true)
                ->where('user_type', $userType)
                ->orderBy('stage')
                ->orderBy('level')
                ->get();
            
            if (Auth::check()) {
                $completedIds = DB::table('user_progress')
                    ->where('user_id', Auth::id())
                    ->where('status', 'completed')
                    ->pluck('scenario_id');
            }
        } catch (\Exception $e) {
            $scenarios = collect([]);
        }

        return view('scenarios.index', compact('scenarios', 'userType', 'completedIds'));
    }

    /**
     * عرض تفاصيل سيناريو
     */
    public function show($id)
    {
        $scenario = DB::table('scenarios')->where('id', $id)->first();
        
        if (!$scenario) {
            return redirect('/scenarios');
        }
        
        return view('scenarios.show', compact('scenario'));
    }

    /**
     * تشغيل السيناريو
     */
    public function play($id)
    {
        $scenario = DB::table('scenarios')->where('id', $id)->first();
        
        if (!$scenario) {
            return redirect('/scenarios');
        }
        
        return view('scenarios.play', compact('scenario'));
    }

    /**
     * إكمال السيناريو
     */
    public function complete(Request $request, $id)
    {
        $user = User::find(Auth::id());
        $scenario = DB::table('scenarios')->where('id', $id)->first();
        
        if (!$scenario || !$user) {
            return response()->json(['success' => false, 'message' => 'Not found']);
        }

        $isCorrect = $request->input('is_correct', false);
        $selectedOption = $request->input('selected_option', null);

        try {
            $existingProgress = DB::table('user_progress')
                ->where('user_id', $user->id)
                ->where('scenario_id', $scenario->id)
                ->where('status', 'completed')
                ->first();
            
            if ($isCorrect) {
                DB::table('user_progress')->updateOrInsert(
                    ['user_id' => $user->id, 'scenario_id' => $scenario->id],
                    [
                        'status' => 'completed',
                        'score' => 100,
                        'completed_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                if (!$existingProgress) {
                    $pointsMap = [
                        'easy' => 10,
                        'medium' => 20,
                        'hard' => 30,
                        'expert' => 50,
                    ];
                    
                    $pointsEarned = $pointsMap[$scenario->level] ?? 10;
                    $currentXP = $user->xp_points ?? 0;
                    $newXP = $currentXP + $pointsEarned;
                    
                    $user->update(['xp_points' => $newXP]);
                    
                    if (method_exists($user, 'updateSecurityRank')) {
                        $user->updateSecurityRank();
                    }
                    
                    $this->updateAwarenessLevel($user->id, $scenario);
                    $this->checkAndAwardBadges($user->id, $scenario);
                    
                    try {
                        DB::table('notifications')->insert([
                            'user_id' => $user->id,
                            'type' => 'success',
                            'title' => 'Scenario Completed!',
                            'message' => 'You completed "' . $scenario->title . '" and earned +' . $pointsEarned . ' XP!',
                            'is_read' => false,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    } catch (\Exception $e) {}
                    
                    return response()->json([
                        'success' => true,
                        'xp_earned' => $pointsEarned,
                        'message' => 'First completion! +' . $pointsEarned . ' XP',
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'xp_earned' => 0,
                        'message' => 'Already completed before.',
                    ]);
                }
            } else {
                DB::table('user_progress')->updateOrInsert(
                    ['user_id' => $user->id, 'scenario_id' => $scenario->id],
                    [
                        'status' => 'failed',
                        'score' => 0,
                        'updated_at' => now(),
                    ]
                );
                
                // ✅ تسجيل الخطأ بشكل صحيح
                $this->recordScenarioMistake($user->id, $scenario, $selectedOption);
                
                return response()->json(['success' => false, 'message' => 'Wrong answer.']);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * ✅ تسجيل خطأ سيناريو في بنك الأخطاء بشكل صحيح
     */
    private function recordScenarioMistake($userId, $scenario, $selectedOption)
    {
        if (!\Schema::hasTable('user_mistakes')) {
            return;
        }

        // استخراج المحتوى من JSON
        $content = [];
        if (!empty($scenario->content)) {
            $content = is_string($scenario->content) 
                ? json_decode($scenario->content, true) 
                : $scenario->content;
        }

        $scenarioText = $content['scenario'] ?? $scenario->description ?? '';
        $question = $content['question'] ?? $scenario->title ?? '';
        $explanation = $content['explanation'] ?? 'No explanation available.';
        $options = $content['options'] ?? [];

        // تحديد الإجابة المختارة والإجابة الصحيحة
        $selectedIndex = $selectedOption !== null ? (int) $selectedOption : null;
        $correctIndex = null;

        foreach ($options as $index => $option) {
            if (!empty($option['correct'])) {
                $correctIndex = $index;
                break;
            }
        }

        $selectedText = ($selectedIndex !== null && isset($options[$selectedIndex])) 
            ? $options[$selectedIndex]['text'] 
            : 'Not answered';
            
        $correctText = ($correctIndex !== null && isset($options[$correctIndex])) 
            ? $options[$correctIndex]['text'] 
            : 'Unknown';

        // تحقق من وجود نفس الخطأ
        $existing = DB::table('user_mistakes')
            ->where('user_id', $userId)
            ->where('scenario_id', $scenario->id)
            ->where('mistake_type', 'scenario')
            ->first();

        if ($existing) {
            DB::table('user_mistakes')
                ->where('id', $existing->id)
                ->update([
                    'user_answer' => $selectedText,
                    'correct_answer' => $correctText,
                    'updated_at' => now(),
                ]);
            return;
        }

        DB::table('user_mistakes')->insert([
            'user_id' => $userId,
            'scenario_id' => $scenario->id,
            'challenge_id' => null,
            'category' => $scenario->category ?? 'general',
            'mistake_type' => 'scenario',
            'scenario_text' => $scenarioText,
            'question' => $question,
            'user_answer' => $selectedText,
            'correct_answer' => $correctText,
            'options' => json_encode($options),
            'explanation' => $explanation,
            'is_reviewed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * تحديث مستوى الوعي
     */
    private function updateAwarenessLevel($userId, $scenario)
    {
        $userType = $scenario->user_type ?? 'general';
        
        $totalScenariosInCategory = DB::table('scenarios')
            ->where('user_type', $userType)
            ->where('is_active', true)
            ->count();
        
        $completedInCategory = DB::table('user_progress')
            ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
            ->where('user_progress.user_id', $userId)
            ->where('user_progress.status', 'completed')
            ->where('scenarios.user_type', $userType)
            ->count();
        
        $percentage = $totalScenariosInCategory > 0 
            ? round(($completedInCategory / $totalScenariosInCategory) * 100) 
            : 0;
        
        $record = DB::table('user_awareness_levels')
            ->where('user_id', $userId)
            ->where('user_type', $userType)
            ->first();
        
        if ($record) {
            DB::table('user_awareness_levels')
                ->where('id', $record->id)
                ->update([
                    'awareness_percentage' => $percentage,
                    'completed_scenarios' => $completedInCategory,
                    'correct_answers' => $completedInCategory,
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('user_awareness_levels')->insert([
                'user_id' => $userId,
                'user_type' => $userType,
                'awareness_percentage' => $percentage,
                'completed_scenarios' => $completedInCategory,
                'correct_answers' => $completedInCategory,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * منح الشارات تلقائياً
     */
    private function checkAndAwardBadges($userId, $scenario)
    {
        try {
            $userType = $scenario->user_type ?? 'general';
            $scenarioLevel = $scenario->level ?? 'easy';
            
            $totalInLevel = DB::table('scenarios')
                ->where('user_type', $userType)
                ->where('level', $scenarioLevel)
                ->where('is_active', true)
                ->count();
            
            $completedInLevel = DB::table('user_progress')
                ->join('scenarios', 'user_progress.scenario_id', '=', 'scenarios.id')
                ->where('user_progress.user_id', $userId)
                ->where('user_progress.status', 'completed')
                ->where('scenarios.user_type', $userType)
                ->where('scenarios.level', $scenarioLevel)
                ->count();
            
            if ($totalInLevel > 0 && $completedInLevel >= $totalInLevel) {
                $badge = DB::table('badges')
                    ->where('category', $userType)
                    ->where('level', $scenarioLevel)
                    ->first();
                
                if ($badge) {
                    $exists = DB::table('user_badges')
                        ->where('user_id', $userId)
                        ->where('badge_id', $badge->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('user_badges')->insert([
                            'user_id' => $userId,
                            'badge_id' => $badge->id,
                            'earned_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
            $completedCount = DB::table('user_progress')
                ->where('user_id', $userId)
                ->where('status', 'completed')
                ->count();
            
            $generalBadges = [];
            if ($completedCount >= 1) $generalBadges[] = 'First Steps';
            if ($completedCount >= 5) $generalBadges[] = 'Security Aware';
            if ($completedCount >= 10) $generalBadges[] = 'Cyber Defender';
            
            foreach ($generalBadges as $badgeName) {
                $badge = DB::table('badges')->where('name', $badgeName)->first();
                
                if ($badge) {
                    $exists = DB::table('user_badges')
                        ->where('user_id', $userId)
                        ->where('badge_id', $badge->id)
                        ->exists();
                    
                    if (!$exists) {
                        DB::table('user_badges')->insert([
                            'user_id' => $userId,
                            'badge_id' => $badge->id,
                            'earned_at' => now(),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            // ignore
        }
    }

    /**
     * بدء السيناريو
     */
    public function start($id)
    {
        return redirect('/scenarios/' . $id . '/play');
    }
}