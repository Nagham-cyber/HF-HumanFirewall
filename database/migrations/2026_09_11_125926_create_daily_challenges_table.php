<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('difficulty')->default('easy');
            $table->integer('points')->default(5);
            $table->text('scenario');
            $table->text('question');
            $table->json('options');
            $table->integer('correct_index')->default(0);
            $table->text('explanation');
            $table->date('challenge_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_challenges');
    }
};