<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('بسم الله الرحمن الرحيم');
        $this->command->info('══════════════════════════════════════');
        $this->command->info('   منصة آل السيحي القرآنية — تهيئة البيانات');
        $this->command->info('══════════════════════════════════════');
        $this->command->info('');

        $this->call([
            SuperAdminSeeder::class,
            BadgesSeeder::class,
            TilawatSeeder::class,

        ]);

        $this->command->info('');
        $this->command->info('✅ اكتملت تهيئة البيانات بحمد الله.');
    }
}