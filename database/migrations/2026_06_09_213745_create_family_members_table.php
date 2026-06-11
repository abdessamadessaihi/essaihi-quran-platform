<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('family_id')
                  ->constrained('families')
                  ->cascadeOnDelete();
            $table->enum('status', [
                'pending', 'active', 'suspended', 'rejected'
            ])->default('pending');
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'family_id']);
            $table->index(['family_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('family_members');
    }
};