<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_wards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->date('ward_date');
            $table->enum('target_unit', [
                'pages', 'hizbs', 'juz', 'ayahs'
            ]);
            $table->decimal('target_value', 5, 2);
            $table->decimal('actual_value', 5, 2)->default(0);
            $table->unsignedSmallInteger('adherence_pct')->default(0)
                  ->comment('0 to 100');
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            // مستخدم واحد = سجل واحد في اليوم
            $table->unique(['user_id', 'ward_date']);
            $table->index('ward_date');
            $table->index(['user_id', 'is_completed']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_wards');
    }
};