<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            if (!Schema::hasColumn('task_submissions', 'admin_reviewed')) {
                $table->boolean('admin_reviewed')->default(false)->after('status');
            }
            if (!Schema::hasColumn('task_submissions', 'admin_approved')) {
                $table->boolean('admin_approved')->default(false)->after('admin_reviewed');
            }
            if (!Schema::hasColumn('task_submissions', 'admin_feedback')) {
                $table->text('admin_feedback')->nullable()->after('admin_approved');
            }
            if (!Schema::hasColumn('task_submissions', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('admin_feedback');
            }
            if (!Schema::hasColumn('task_submissions', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $columns = ['admin_reviewed', 'admin_approved', 'admin_feedback', 'reviewed_at', 'reviewed_by'];
            foreach ($columns as $col) {
                if (Schema::hasColumn('task_submissions', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};