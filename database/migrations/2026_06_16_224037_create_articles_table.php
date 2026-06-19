<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()->cascadeOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('cover_url', 500)->nullable();
            $table->enum('category', [
                'tafsir',      // تفسير
                'tadabbur',    // تدبر
                'fiqh',        // فقه
                'seerah',      // سيرة
                'general',     // عام
            ])->default('tadabbur');
            $table->enum('status', ['draft','published'])
                  ->default('draft');
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->timestamps();

            $table->index(['status','created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};