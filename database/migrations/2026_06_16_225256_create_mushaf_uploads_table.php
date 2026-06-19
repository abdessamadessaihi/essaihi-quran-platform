<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mushaf_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                  ->constrained()->cascadeOnDelete();
            $table->string('title', 150);
            $table->string('file_url', 500);
            $table->string('file_name', 250);
            $table->unsignedBigInteger('file_size')->default(0);
            $table->enum('file_type', ['pdf','image'])->default('pdf');
            $table->unsignedSmallInteger('last_page')->default(1);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mushaf_uploads');
    }
};