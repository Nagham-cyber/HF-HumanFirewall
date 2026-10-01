<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class AddChallengeIdToMistakes extends Seeder
{
    public function run(): void
    {
        Schema::table('user_mistakes', function ($table) {
            if (!Schema::hasColumn('user_mistakes', 'challenge_id')) {
                $table->foreignId('challenge_id')->nullable();
            }
        });
        echo "challenge_id added!\n";
    }
}