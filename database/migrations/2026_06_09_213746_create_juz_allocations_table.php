<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('juz_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khatma_id')
                  ->constrained('khatmas')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->unsignedTinyInteger('juz_number')
                  ->comment('1 to 30');
            $table->enum('status', [
                'available', 'reserved', 'reading',
                'completed', 'expired'
            ])->default('available');
            $table->timestamp('claimed_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('deadline_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // Mutex: يمنع حجز نفس الجزء مرتين في نفس الختمة
            $table->unique(['khatma_id', 'juz_number']);
            $table->index(['user_id', 'status']);
            $table->index(['khatma_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('juz_allocations');
    }
};