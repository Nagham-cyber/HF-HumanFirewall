<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        Admin::firstOrCreate(
            ['email' => 'admin@hf.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('HF@Admin2026!Secure'),
                'role' => 'super_admin',
                'two_factor_enabled' => false,
                'is_active' => true,
            ]
        );

        echo "\n";
        echo "========================================\n";
        echo "  ADMIN ACCOUNT CREATED\n";
        echo "========================================\n";
        echo "  Email: admin@hf.com\n";
        echo "  Password: HF@Admin2026!Secure\n";
        echo "========================================\n";
        echo "  ⚠️  CHANGE THIS PASSWORD IMMEDIATELY!\n";
        echo "========================================\n\n";
    }
}