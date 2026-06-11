<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leaderboard_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('family_id')
                  ->nullable()
                  ->constrained('families')
                  ->cascadeOnDelete();
            $table->enum('period_type', [
                'weekly', 'monthly', 'alltime'
            ]);
            $table->string('period_key', 20)
                  ->comment('e.g. 2025-W42 or 2025-11');
            $table->enum('category', [
                'reading', 'memorization',
                'streak', 'khatma'
            ]);
            $table->unsignedInteger('score')->default(0);
            $table->unsignedSmallInteger('rank')->default(0);
            $table->timestamp('calculated_at')->useCurrent();

            $table->unique(
                ['user_id', 'period_type', 'period_key', 'category'],
                'unique_user_leaderboard'
            );
            $table->index(['period_key', 'category', 'rank']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leaderboard_entries');
    }
};