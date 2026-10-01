<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cyber_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('reference_code')->unique();
            $table->string('section');
            $table->string('title');
            $table->text('description');
            $table->string('tools')->nullable();
            $table->string('estimated_time')->default('2 hours');
            $table->integer('difficulty')->default(3);
            $table->integer('points')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained('cyber_tasks')->onDelete('cascade');
            $table->string('status')->default('in_progress');
            $table->text('step_by_step')->nullable();
            $table->text('observations')->nullable();
            $table->text('lessons_learned')->nullable();
            $table->integer('difficulty_rating')->nullable();
            $table->boolean('xp_awarded')->default(false);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
        Schema::dropIfExists('cyber_tasks');
    }
};