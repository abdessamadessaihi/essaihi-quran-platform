<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tilawats', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان التلاوة (مثلاً: ما تيسر من سورة البقرة)
            $table->string('reciter_name'); // اسم الشيخ القارئ (مثلاً: عبد الباسط عبد الصمد)
            $table->string('surah_name')->nullable(); // اسم السورة المرتبطة إن وجد
            $table->enum('media_type', ['mp4', 'youtube', 'mp3'])->default('mp4'); // نوع الوسائط
            $table->text('media_url'); // رابط ملف الفيديو أو الصوت
            $table->string('cover_image')->nullable(); // صورة غلاف تظهر قبل التشغيل (اختياري)
            $table->integer('views_count')->default(0); // عداد المشاهدات للتشجيع
            $table->boolean('is_featured')->default(false); // تمييز التلاوات المختارة في الأعلى
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tilawats');
    }
};