<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class PasswordResetCodesTableSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('password_reset_codes')) {
            Schema::create('password_reset_codes', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('code');
                $table->timestamp('expires_at');
                $table->timestamps();
            });
            echo "password_reset_codes created!\n";
        } else {
            echo "Table already exists!\n";
        }
    }
}