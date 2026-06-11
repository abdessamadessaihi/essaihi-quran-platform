<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'super_admin',
                'family_admin',
                'member'
            ])->default('member')->after('email');

            $table->string('phone', 20)->nullable()->after('role');
            $table->string('avatar_url', 500)->nullable()->after('phone');
            $table->string('locale', 10)->default('ar')->after('avatar_url');
            $table->boolean('is_active')->default(true)->after('locale');

            $table->index('role');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role', 'phone', 'avatar_url', 'locale', 'is_active'
            ]);
        });
    }
};