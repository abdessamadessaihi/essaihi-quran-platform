<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Streak;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@essaihi.com'],
            [
                'name'              => 'المدير العام',
                'password'          => Hash::make('Admin@Essaihi2025'),
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