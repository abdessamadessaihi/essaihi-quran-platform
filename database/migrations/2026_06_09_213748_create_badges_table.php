<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('badges', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 110)->unique();
            $table->text('description')->nullable();
            $table->string('icon_url', 500)->nullable();
            $table->enum('category', [
                'reading', 'memorization',
                'streak', 'khatma', 'social'
            ]);
            $table->json('criteria')->nullable()
                  ->comment('Flexible badge award conditions');
            $table->unsignedInteger('xp_reward')->default(0);
            $table->timestamp('created_at')->useCurrent();

            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('badges');
    }
};