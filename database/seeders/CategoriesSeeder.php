<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ==================== SCENARIO CATEGORIES ====================
            [
                'name' => 'Phishing',
                'slug' => 'phishing',
                'icon' => 'phishing',
                'color' => 'text-[#4ADE80]',
                'description' => 'Email and message-based phishing attacks',
                'type' => 'scenario',
                'order' => 1,
            ],
            [
                'name' => 'Password Security',
                'slug' => 'password',
                'icon' => 'key',
                'color' => 'text-[#60A5FA]',
                'description' => 'Password management and authentication',
                'type' => 'scenario',
                'order' => 2,
            ],
            [
                'name' => 'Malware',
                'slug' => 'malware',
                'icon' => 'bug_report',
                'color' => 'text-red-400',
                'description' => 'Viruses, ransomware, and malicious software',
                'type' => 'scenario',
                'order' => 3,
            ],
            [
                'name' => 'Social Engineering',
                'slug' => 'social_engineering',
                'icon' => 'psychology',
                'color' => 'text-purple-400',
                'description' => 'Manipulation tactics used by attackers',
                'type' => 'scenario',
                'order' => 4,
            ],
            [
                'name' => 'Network Security',
                'slug' => 'network',
                'icon' => 'router',
                'color' => 'text-[#4ADE80]',
                'description' => 'Network-based attacks and defenses',
                'type' => 'scenario',
                'order' => 5,
            ],
            [
                'name' => 'Physical Security',
                'slug' => 'physical',
                'icon' => 'badge',
                'color' => 'text-orange-400',
                'description' => 'Physical access and facility security',
                'type' => 'scenario',
                'order' => 6,
            ],
            [
                'name' => 'Data Security',
                'slug' => 'data',
                'icon' => 'storage',
                'color' => 'text-[#60A5FA]',
                'description' => 'Data protection and privacy',
                'type' => 'scenario',
                'order' => 7,
            ],
            [
                'name' => 'Cloud Security',
                'slug' => 'cloud',
                'icon' => 'cloud',
                'color' => 'text-[#60A5FA]',
                'description' => 'Cloud infrastructure and services security',
                'type' => 'scenario',
                'order' => 8,
            ],
            [
                'name' => 'Mobile Security',
                'slug' => 'mobile',
                'icon' => 'smartphone',
                'color' => 'text-[#4ADE80]',
                'description' => 'Smartphone and tablet security',
                'type' => 'scenario',
                'order' => 9,
            ],
            [
                'name' => 'Insider Threat',
                'slug' => 'insider',
                'icon' => 'person_off',
                'color' => 'text-red-400',
                'description' => 'Internal threats and data leakage',
                'type' => 'scenario',
                'order' => 10,
            ],

            // ==================== CYBER TASK SECTIONS ====================
            [
                'name' => 'Network Defense',
                'slug' => 'network_defense',
                'icon' => 'security',
                'color' => 'text-[#4ADE80]',
                'description' => 'Network & Cloud Defense',
                'type' => 'task',
                'order' => 20,
            ],
            [
                'name' => 'DFIR',
                'slug' => 'dfir',
                'icon' => 'forensics',
                'color' => 'text-red-400',
                'description' => 'Digital Forensics & Incident Response',
                'type' => 'task',
                'order' => 21,
            ],
            [
                'name' => 'Pentest',
                'slug' => 'pentest',
                'icon' => 'bug_report',
                'color' => 'text-purple-400',
                'description' => 'Penetration Testing & Red Teaming',
                'type' => 'task',
                'order' => 22,
            ],
            [
                'name' => 'DevSecOps',
                'slug' => 'devsecops',
                'icon' => 'code',
                'color' => 'text-[#60A5FA]',
                'description' => 'Secure Development & Operations',
                'type' => 'task',
                'order' => 23,
            ],
            [
                'name' => 'GRC',
                'slug' => 'grc',
                'icon' => 'gavel',
                'color' => 'text-yellow-400',
                'description' => 'Governance, Risk & Compliance',
                'type' => 'task',
                'order' => 24,
            ],
            [
                'name' => 'Automation',
                'slug' => 'automation',
                'icon' => 'smart_toy',
                'color' => 'text-orange-400',
                'description' => 'Security Automation & AI',
                'type' => 'task',
                'order' => 25,
            ],
        ];

        foreach ($categories as $category) {
            $category['is_active'] = true;
            $category['created_at'] = now();
            $category['updated_at'] = now();

            // استخدم updateOrInsert لتجنب التكرار
            DB::table('categories')->updateOrInsert(
                ['slug' => $category['slug']],
                $category
            );
        }

        echo "Categories seeded successfully!\n";
        echo "Total: " . count($categories) . " categories\n";
        echo "  - Scenario categories: 10\n";
        echo "  - Task sections: 6\n";
    }
}