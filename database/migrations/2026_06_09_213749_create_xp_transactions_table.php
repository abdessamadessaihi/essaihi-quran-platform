<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('xp_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->integer('points')
                  ->comment('Positive or negative XP');
            $table->enum('source_type', [
                'ward', 'juz', 'revision',
                'badge', 'khatma', 'memorization'
            ]);
            $table->unsignedBigInteger('source_id')
                  ->nullable()
                  ->comment('Polymorphic reference');
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('user_id');
            $table->index(['source_type', 'source_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_transactions');
    }
};