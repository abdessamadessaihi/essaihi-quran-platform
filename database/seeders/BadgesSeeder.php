<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Badge;

class BadgesSeeder extends Seeder
{
    public function run(): void
    {
        $badges = [

            // ── شارات القراءة ─────────────────────────────
            [
                'name'        => 'قارئ مبتدئ',
                'slug'        => 'reader-beginner',
                'description' => 'أكملت ورد يومي لأول مرة',
                'category'    => Badge::CATEGORY_READING,
                'criteria'    => ['wards_completed' => 1],
                'xp_reward'   => 50,
            ],
            [
                'name'        => 'قارئ متميز',
                'slug'        => 'reader-distinguished',
                'description' => 'أكملت 30 ورد يومي',
                'category'    => Badge::CATEGORY_READING,
                'criteria'    => ['wards_completed' => 30],
                'xp_reward'   => 200,
            ],
            [
                'name'        => 'صاحب الهمة',
                'slug'        => 'reader-dedicated',
                'description' => 'أكملت 100 ورد يومي',
                'category'    => Badge::CATEGORY_READING,
                'criteria'    => ['wards_completed' => 100],
                'xp_reward'   => 500,
            ],

            // ── شارات الحفظ ───────────────────────────────
            [
                'name'        => 'حافظ مبتدئ',
                'slug'        => 'memorizer-beginner',
                'description' => 'حفظت أول جزء من القرآن الكريم',
                'category'    => Badge::CATEGORY_MEMORIZATION,
                'criteria'    => ['juz_memorized' => 1],
                'xp_reward'   => 300,
            ],
            [
                'name'        => 'حافظ نصف القرآن',
                'slug'        => 'memorizer-half',
                'description' => 'أتممت حفظ نصف القرآن الكريم — 15 جزءًا',
                'category'    => Badge::CATEGORY_MEMORIZATION,
                'criteria'    => ['juz_memorized' => 15],
                'xp_reward'   => 1500,
            ],
            [
                'name'        => 'حافظ القرآن',
                'slug'        => 'memorizer-complete',
                'description' => 'أتممت حفظ القرآن الكريم كاملًا بفضل الله',
                'category'    => Badge::CATEGORY_MEMORIZATION,
                'criteria'    => ['juz_memorized' => 30],
                'xp_reward'   => 5000,
            ],

            // ── شارات الالتزام (Streak) ───────────────────
            [
                'name'        => 'أسبوع من الالتزام',
                'slug'        => 'streak-week',
                'description' => '7 أيام متتالية من القراءة',
                'category'    => Badge::CATEGORY_STREAK,
                'criteria'    => ['streak_days' => 7],
                'xp_reward'   => 100,
            ],
            [
                'name'        => 'شهر من الالتزام',
                'slug'        => 'streak-month',
                'description' => '30 يومًا متتاليًا من القراءة',
                'category'    => Badge::CATEGORY_STREAK,
                'criteria'    => ['streak_days' => 30],
                'xp_reward'   => 400,
            ],
            [
                'name'        => 'المواظب الذهبي',
                'slug'        => 'streak-golden',
                'description' => '100 يوم متتالي لا ينقطع',
                'category'    => Badge::CATEGORY_STREAK,
                'criteria'    => ['streak_days' => 100],
                'xp_reward'   => 1000,
            ],

            // ── شارات الختمة ──────────────────────────────
            [
                'name'        => 'أول ختمة',
                'slug'        => 'khatma-first',
                'description' => 'أكملت أول ختمة قرآنية',
                'category'    => Badge::CATEGORY_KHATMA,
                'criteria'    => ['khatmas_completed' => 1],
                'xp_reward'   => 250,
            ],
            [
                'name'        => 'ختمة رمضان',
                'slug'        => 'khatma-ramadan',
                'description' => 'أكملت ختمة قرآنية في شهر رمضان المبارك',
                'category'    => Badge::CATEGORY_KHATMA,
                'criteria'    => ['khatma_type' => 'ramadan'],
                'xp_reward'   => 600,
            ],

            // ── شارات اجتماعية ────────────────────────────
            [
                'name'        => 'عضو العائلة',
                'slug'        => 'social-family-member',
                'description' => 'انضممت إلى دائرة العائلة القرآنية',
                'category'    => Badge::CATEGORY_SOCIAL,
                'criteria'    => ['joined_family' => true],
                'xp_reward'   => 75,
            ],
            [
                'name'        => 'روح الجماعة',
                'slug'        => 'social-team-spirit',
                'description' => 'شاركت في 5 ختمات جماعية',
                'category'    => Badge::CATEGORY_SOCIAL,
                'criteria'    => ['group_khatmas' => 5],
                'xp_reward'   => 350,
            ],
        ];

        foreach ($badges as $badge) {
            Badge::updateOrCreate(
                ['slug' => $badge['slug']],
                $badge
            );
        }

        $this->command->info('✅ تم إنشاء ' . count($badges) . ' شارة بنجاح.');
    }
}