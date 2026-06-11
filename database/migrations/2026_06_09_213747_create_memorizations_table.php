<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('memorizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->unsignedTinyInteger('surah_number')
                  ->comment('1 to 114');
            $table->unsignedSmallInteger('ayah_from');
            $table->unsignedSmallInteger('ayah_to');
            $table->enum('mastery_level', [
                'weak', 'fair', 'good', 'excellent'
            ])->default('fair');
            $table->date('memorized_at');
            $table->date('last_reviewed_at')->nullable();
            $table->unsignedSmallInteger('review_score')
                  ->default(0)
                  ->comment('0 to 100');
            $table->timestamps();

            $table->index(['user_id', 'surah_number']);
            $table->index('mastery_level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('memorizations');
    }
};