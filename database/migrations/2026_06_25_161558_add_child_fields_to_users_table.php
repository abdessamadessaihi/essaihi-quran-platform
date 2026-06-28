<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // جعل البريد الإلكتروني وكلمة المرور nullable لحسابات الأطفال فقط
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();

            // إضافة حقول ربط الطفل بولي أمره
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('pin_code')->nullable()->after('username'); // رمز الدخول من 4 أرقام
            $table->integer('age')->nullable()->after('pin_code');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->nullable(false)->change();
            $table->string('password')->nullable(false)->change();
            
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'username', 'pin_code', 'age']);
        });
    }
};