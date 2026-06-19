<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('quran_classes', function (Blueprint $table) {
        $table->id();
        $table->foreignId('mohafid_id')->constrained('users')->onDelete('cascade'); // المحفظ المسؤول
        $table->string('title'); // اسم الحلقة أو الحصة (مثلاً: حلقة التجويد برواية ورش)
        $table->text('description')->nullable();
        $table->string('meet_url')->nullable(); // رابط Google Meet أو Zoom
        $table->text('courses_materials')->nullable(); // الدروس المرفقة أو الملاحظات
        $table->string('schedule')->nullable(); // توقيت الحصة (مثال: السبت والإثنين 18:00)
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quran_classes');
    }
};
