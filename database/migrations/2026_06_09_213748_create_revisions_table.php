<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('memorization_id')
                  ->constrained('memorizations')
                  ->cascadeOnDelete();
            $table->enum('revision_type', [
                'daily', 'weekly', 'monthly'
            ]);
            $table->enum('status', [
                'pending', 'completed', 'skipped', 'overdue'
            ])->default('pending');
            $table->date('scheduled_date');
            $table->date('completed_date')->nullable();
            $table->unsignedSmallInteger('score')
                  ->nullable()
                  ->comment('0 to 100');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['user_id', 'scheduled_date', 'status']);
            $table->index('memorization_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};