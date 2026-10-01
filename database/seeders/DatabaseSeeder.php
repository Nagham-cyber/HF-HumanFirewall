<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==================== ROLES ====================
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // ==================== PERMISSIONS ====================
        $permissions = [
            'view-dashboard',
            'view-scenarios',
            'start-scenario',
            'complete-scenario',
            'view-badges',
            'view-progress',
            'use-ai-assistant',
            'manage-scenarios',
            'manage-users',
            'manage-campaigns',
            'view-analytics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ==================== ASSIGN PERMISSIONS ====================
        $adminRole->syncPermissions(Permission::all());
        $userRole->syncPermissions([
            'view-dashboard',
            'view-scenarios',
            'start-scenario',
            'complete-scenario',
            'view-badges',
            'view-progress',
            'use-ai-assistant',
        ]);

        // ==================== TEST USERS ====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@sentinelplay.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'),
                'language_preference' => 'en',
                'xp_points' => 0,
                'security_rank' => 'Security Novice',
                'streak_days' => 0,
                'is_active' => true,
            ]
        );
        $admin->assignRole('admin');

        $user = User::firstOrCreate(
            ['email' => 'user@sentinelplay.com'],
            [
                'name' => 'Test User',
                'password' => Hash::make('password123'),
                'language_preference' => 'en',
                'xp_points' => 0,
                'security_rank' => 'Security Novice',
                'streak_days' => 0,
                'is_active' => true,
            ]
        );
        $user->assignRole('user');

        // ==================== CYBER TASKS ====================
        $this->call([
    CyberTasksSeeder::class,
    \Database\Seeders\CyberTasksPhase4Seeder::class,
    \Database\Seeders\CyberTasksPhase5Seeder::class,
    \Database\Seeders\CyberTasksPhase6Seeder::class,
    \Database\Seeders\CyberTasksPhase7Seeder::class,
    \Database\Seeders\CyberTasksPhase8Seeder::class,
    \Database\Seeders\CyberTasksPhase9Seeder::class,
    \Database\Seeders\CyberTasksPhase10Seeder::class,
    \Database\Seeders\CyberTasksPhase11Seeder::class,
]);
    }
}