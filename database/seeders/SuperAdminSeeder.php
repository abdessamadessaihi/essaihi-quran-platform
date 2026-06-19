<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Streak;
use Illuminate\Support\Facades\Hash;


use App\Models\Tilawat;

class TilawatSeeder extends Seeder
{
    public function run(): void
    {
        Tilawat::create([
            'title' => 'ما تيسر من سورة الفتح - تلاوة حزينة ومبكية',
            'reciter_name' => 'الشيخ عبد الباسط عبد الصمد',
            'surah_name' => 'سورة الفتح',
            'media_type' => 'mp4',
            'media_url' => 'https://www.w3schools.com/html/mov_bbb.mp4', // استبدله برابط mp4 حقيقي للتلاوة
            'is_featured' => true
        ]);

        Tilawat::create([
            'title' => 'سورة يس كاملة بجودة عالية',
            'reciter_name' => 'الشيخ محمد صديق المنشاوي',
            'surah_name' => 'سورة يس',
            'media_type' => 'mp3',
            'media_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3', // استبدله برابط صوتي للقرآن
            'is_featured' => false
        ]);
    }
}

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@essaihi.com'],
            [
                'name'              => 'المدير العام',
                'password'          => Hash::make('Admin@2026'),
                'role'              => User::ROLE_SUPER_ADMIN,
                'locale'            => 'ar',
                'is_active'         => true,
                'email_verified_at' => now(),
            ]
        );

        // إنشاء سجل Streak فارغ للمدير
        Streak::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'current_streak'   => 0,
                'longest_streak'   => 0,
                'total_active_days'=> 0,
            ]
        );

        $this->command->info('✅ تم إنشاء حساب المدير العام بنجاح.');
        $this->command->line('   البريد: admin@essaihi.com');
        $this->command->line('   كلمة المرور: Admin@Essaihi');
    }
}