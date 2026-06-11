<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khatmas', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->enum('type', [
                'platform', 'family', 'individual',
                'ramadan', 'weekly', 'monthly'
            ]);
            $table->enum('status', [
                'draft', 'active', 'completed', 'cancelled'
            ])->default('draft');
            $table->foreignId('created_by')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->foreignId('family_id')
                  ->nullable()
                  ->constrained('families')
                  ->cascadeOnDelete();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->boolean('auto_distribute')->default(false);
            $table->unsignedTinyInteger('completed_juz_count')->default(0);
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('family_id');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khatmas');
    }
};