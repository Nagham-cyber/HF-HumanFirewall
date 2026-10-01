<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AwardExistingBadges extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();
        
        foreach ($users as $user) {
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
                    
                    if ($totalInLevel > 0 && $completedInLevel >= $totalInLevel) {
                        $badge = DB::table('badges')
                            ->where('category', $category)
                            ->where('level', $level)
                            ->first();
                        
                        if ($badge) {
                            $exists = DB::table('user_badges')
                                ->where('user_id', $user->id)
                                ->where('badge_id', $badge->id)
                                ->exists();
                            
                            if (!$exists) {
                                DB::table('user_badges')->insert([
                                    'user_id' => $user->id,
                                    'badge_id' => $badge->id,
                                    'earned_at' => now(),
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ]);
                                echo "Awarded: {$badge->name} to User {$user->id}\n";
                            }
                        }
                    }
                }
            }
        }
        
        echo "Done!\n";
    }
}