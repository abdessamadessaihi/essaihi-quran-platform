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
        Schema::table('daily_wards', function (Blueprint $table) {
            // ربط الورد بالختمة الشخصية
            $table->foreignId('khatma_id')
                  ->nullable()
                  ->constrained('khatmas')
                  ->cascadeOnDelete();

            // نوع التحديد: page, surah, hizb, juz
            $table->enum('location_type', ['page', 'surah', 'hizb', 'juz'])
                  ->nullable();

            // نقطة البداية
            $table->unsignedSmallInteger('start_page')->nullable();
            $table->unsignedSmallInteger('start_surah')->nullable();
            $table->unsignedSmallInteger('start_hizb')->nullable();
            $table->unsignedSmallInteger('start_juz')->nullable();

            // نقطة النهاية
            $table->unsignedSmallInteger('end_page')->nullable();
            $table->unsignedSmallInteger('end_surah')->nullable();
            $table->unsignedSmallInteger('end_hizb')->nullable();
            $table->unsignedSmallInteger('end_juz')->nullable();

            // ملاحظات إضافية
            $table->text('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_wards', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\Khatma::class);
            $table->dropColumn([
                'khatma_id',
                'location_type',
                'start_page',
                'start_surah',
                'start_hizb',
                'start_juz',
                'end_page',
                'end_surah',
                'end_hizb',
                'end_juz',
                'notes',
            ]);
        });
    }
};
