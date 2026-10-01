<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('badges')->delete();

        $badges = [
            // ============ المستخدم العادي ============
            [
                'name' => 'General Beginner',
                'description' => 'Completed all easy scenarios for General Users',
                'tier' => 'bronze',
                'icon' => 'person',
                'color' => 'from-amber-400 to-orange-600',
                'category' => 'general',
                'level' => 'easy',
                'is_active' => true,
            ],
            [
                'name' => 'General Intermediate',
                'description' => 'Completed all medium scenarios for General Users',
                'tier' => 'silver',
                'icon' => 'verified_user',
                'color' => 'from-gray-300 to-gray-500',
                'category' => 'general',
                'level' => 'medium',
                'is_active' => true,
            ],
            [
                'name' => 'General Advanced',
                'description' => 'Completed all hard scenarios for General Users',
                'tier' => 'gold',
                'icon' => 'military_tech',
                'color' => 'from-yellow-400 to-amber-600',
                'category' => 'general',
                'level' => 'hard',
                'is_active' => true,
            ],
            [
                'name' => 'General Expert',
                'description' => 'Completed all expert scenarios for General Users',
                'tier' => 'platinum',
                'icon' => 'workspace_premium',
                'color' => 'from-purple-400 to-fuchsia-600',
                'category' => 'general',
                'level' => 'expert',
                'is_active' => true,
            ],
            
            // ============ مهندس IT ============
            [
                'name' => 'IT Beginner',
                'description' => 'Completed all easy scenarios for IT Professionals',
                'tier' => 'bronze',
                'icon' => 'computer',
                'color' => 'from-blue-400 to-cyan-600',
                'category' => 'it_professional',
                'level' => 'easy',
                'is_active' => true,
            ],
            [
                'name' => 'IT Intermediate',
                'description' => 'Completed all medium scenarios for IT Professionals',
                'tier' => 'silver',
                'icon' => 'dns',
                'color' => 'from-sky-400 to-blue-600',
                'category' => 'it_professional',
                'level' => 'medium',
                'is_active' => true,
            ],
            [
                'name' => 'IT Advanced',
                'description' => 'Completed all hard scenarios for IT Professionals',
                'tier' => 'gold',
                'icon' => 'security',
                'color' => 'from-emerald-400 to-green-600',
                'category' => 'it_professional',
                'level' => 'hard',
                'is_active' => true,
            ],
            [
                'name' => 'IT Expert',
                'description' => 'Completed all expert scenarios for IT Professionals',
                'tier' => 'platinum',
                'icon' => 'admin_panel_settings',
                'color' => 'from-violet-400 to-purple-600',
                'category' => 'it_professional',
                'level' => 'expert',
                'is_active' => true,
            ],
            
            // ============ خبير أمن ============
            [
                'name' => 'Security Beginner',
                'description' => 'Completed all easy scenarios for Security Experts',
                'tier' => 'bronze',
                'icon' => 'shield',
                'color' => 'from-red-400 to-rose-600',
                'category' => 'security_expert',
                'level' => 'easy',
                'is_active' => true,
            ],
            [
                'name' => 'Security Intermediate',
                'description' => 'Completed all medium scenarios for Security Experts',
                'tier' => 'silver',
                'icon' => 'gpp_good',
                'color' => 'from-pink-400 to-rose-600',
                'category' => 'security_expert',
                'level' => 'medium',
                'is_active' => true,
            ],
            [
                'name' => 'Security Advanced',
                'description' => 'Completed all hard scenarios for Security Experts',
                'tier' => 'gold',
                'icon' => 'local_police',
                'color' => 'from-orange-400 to-red-600',
                'category' => 'security_expert',
                'level' => 'hard',
                'is_active' => true,
            ],
            [
                'name' => 'Security Expert',
                'description' => 'Completed all expert scenarios for Security Experts',
                'tier' => 'platinum',
                'icon' => 'shield_moon',
                'color' => 'from-fuchsia-400 to-purple-600',
                'category' => 'security_expert',
                'level' => 'expert',
                'is_active' => true,
            ],
        ];

        foreach ($badges as $badge) {
            $badge['created_at'] = now();
            $badge['updated_at'] = now();
            DB::table('badges')->insert($badge);
        }

        echo "Total badges: " . count($badges) . "\n";
    }
}