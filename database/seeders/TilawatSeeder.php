<?php

namespace Database\Seeders;

use App\Models\Tilawat;
use Illuminate\Database\Seeder;

class TilawatSeeder extends Seeder
{
    public function run(): void
    {
        Tilawat::create([
            'title' => 'ما تيسر من سورة الفتح - تلاوة حزينة ومبكية',
            'reciter_name' => 'الشيخ عبد الباسط عبد الصمد',
            'surah_name' => 'سورة الفتح',
            'media_type' => 'youtube',
            'media_url' => 'https://youtu.be/M2fikE4NgMg?si=XQQx0ruN0dVso2gt', 
            'is_featured' => false
        ]);

        Tilawat::create([
            'title' => 'سورة يس كاملة بجودة عالية',
            'reciter_name' => 'الشيخ محمد صديق المنشاوي',
            'surah_name' => 'سورة يس',
            'media_type' => 'youtube',
            'media_url' => 'https://youtu.be/0dFMfvdPWWs?si=GI76OuspiRvOUs2a', 
            'is_featured' => false
        ]);
        

      
    }
}