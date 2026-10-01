<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->string('sender_type')->default('user'); // user | admin
            $table->string('message_type')->default('direct'); // direct | announcement
            $table->text('encrypted_subject');
            $table->text('encrypted_body');
            $table->string('encryption_key_id')->default('v1');
            $table->boolean('is_read')->default(false);
            $table->boolean('is_replied')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['receiver_id', 'is_read']);
            $table->index(['admin_id', 'created_at']);
            $table->index(['message_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};