<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Khatma;
use App\Models\Family;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * الدالة الرئيسية لإنشاء إشعار
     */
    public static function create(
        int    $userId,
        string $type,
        string $title,
        string $message,
        string $icon = '🔔',
        array  $extra = []
    ): void {
        try {
            Notification::create([
                'user_id' => $userId,
                'type'    => $type,
                'data'    => array_merge([
                    'title'   => $title,
                    'message' => $message,
                    'icon'    => $icon,
                ], $extra),
                'channel' => 'database',
                'is_read' => false,
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error("NotificationService::create failed: " . $e->getMessage());
        }
    }

    /**
     * منع إرسال نفس الإشعار مرتين في نفس اليوم
     */
    private static function alreadySentToday(int $userId, string $type): bool
    {
        return Notification::where('user_id', $userId)
            ->where('type', $type)
            ->whereDate('created_at', today())
            ->exists();
    }

    /**
     * إشعار عند إنشاء ختمة جديدة
     */
    public static function onKhatmaCreated(Khatma $khatma): void
    {
        $creatorId = $khatma->created_by;

        if ($khatma->type === 'platform') {
            // ختمة عامة → إشعار للجميع
            $users = User::where('is_active', true)
                         ->where('id', '!=', $creatorId)
                         ->get();

            foreach ($users as $user) {
                self::create(
                    $user->id,
                    'NewKhatma',
                    '📚 ختمة قرآنية جديدة',
                    "تم إطلاق ختمة جديدة: \"{$khatma->title}\". انضم الآن وشارك في إحياء كتاب الله.",
                    '📚',
                    ['khatma_id' => $khatma->id]
                );
            }

            Log::info("Sent NewKhatma notifications to " . $users->count() . " users");

        } elseif (in_array($khatma->type, ['family']) && $khatma->family_id) {
            // ختمة عائلية → إشعار لأعضاء العائلة
            $family  = Family::find($khatma->family_id);
            if (!$family) return;

            $members = $family->activeMembers()
                ->where('users.id', '!=', $creatorId)
                ->get();

            foreach ($members as $member) {
                self::create(
                    $member->id,
                    'NewFamilyKhatma',
                    '👨‍👩‍👧 ختمة عائلية جديدة',
                    "أنشأ أحد أفراد عائلتك ختمة جديدة: \"{$khatma->title}\". بادر بالانضمام.",
                    '📚',
                    ['khatma_id' => $khatma->id, 'family_id' => $family->id]
                );
            }
        } else {
            // ختمات رمضان، أسبوعية، شهرية → إشعار لجميع المستخدمين النشطين
            $users = User::where('is_active', true)
                         ->where('id', '!=', $creatorId)
                         ->get();

            foreach ($users as $user) {
                self::create(
                    $user->id,
                    'NewKhatma',
                    '📚 ختمة قرآنية جديدة',
                    "ختمة جديدة متاحة: \"{$khatma->title}\" — " .
                    match($khatma->type) {
                        'ramadan'    => '🌙 رمضانية',
                        'weekly'     => '📅 أسبوعية',
                        'monthly'    => '📆 شهرية',
                        'individual' => '👤 فردية',
                        default      => '',
                    },
                    '📚',
                    ['khatma_id' => $khatma->id]
                );
            }
        }
    }

    /**
     * إشعار عند نشر مقال تدبري
     */
    public static function onArticlePublished(Article $article): void
    {
        $article->loadMissing('author');

        $users = User::where('is_active', true)
                     ->where('id', '!=', $article->user_id)
                     ->get();

        foreach ($users as $user) {
            self::create(
                $user->id,
                'NewArticle',
                '✍️ مقال تدبري جديد',
                "نشر {$article->author->name} مقالاً جديداً: \"{$article->title}\"",
                '📝',
                ['article_id' => $article->id]
            );
        }

        Log::info("Sent NewArticle notifications to " . $users->count() . " users");
    }

    /**
     * إشعار عند طلب الانضمام لعائلة
     */
    public static function onMemberJoined(int $familyId, User $newMember): void
    {
        $family = Family::find($familyId);
        if (!$family) return;

        $admins = $family->memberships()
            ->where('role', 'admin')
            ->where('status', 'active')
            ->pluck('user_id');

        foreach ($admins as $adminId) {
            self::create(
                $adminId,
                'NewMemberRequest',
                '👤 طلب انضمام جديد',
                "طلب {$newMember->name} الانضمام إلى عائلة {$family->name}.",
                '👋',
                ['family_id' => $familyId, 'member_name' => $newMember->name]
            );
        }
    }
    /**
     * إشعار عند قبول طلب الانضمام إلى العائلة
     */
    public static function onFamilyRequestAccepted(int $familyId, int $userId): void
    {
        $family = Family::find($familyId);
        if (!$family) return;

        self::create(
            $userId,
            'FamilyRequestAccepted',
            '✅ تم قبول انضمامك للعائلة',
            "مرحباً بك! وافق مسؤول عائلة \"{$family->name}\" على طلب انضمامك. يمكنك الآن المشاركة في الختمات العائلية.",
            '🎉',
            ['family_id' => $familyId]
        );
    }

    /**
     * إشعار عند رفض طلب الانضمام إلى العائلة
     */
    public static function onFamilyRequestRejected(int $familyId, int $userId): void
    {
        $family = Family::find($familyId);
        if (!$family) return;

        self::create(
            $userId,
            'FamilyRequestRejected',
            '❌ نعتذر، تم رفض طلب الانضمام',
            "تم رفض طلب انضمامك إلى عائلة \"{$family->name}\" من قِبل مسؤول العائلة.",
            '⚠️',
            ['family_id' => $familyId]
        );
    }

    /**
     * إشعار عند اكتمال الختمة
     */
    public static function onKhatmaCompleted(Khatma $khatma): void
    {
        $participants = $khatma->juzAllocations()
            ->whereNotNull('user_id')
            ->pluck('user_id')
            ->unique();

        foreach ($participants as $userId) {
            self::create(
                $userId,
                'KhatmaCompleted',
                '🎉 اكتملت الختمة',
                "اكتملت ختمة \"{$khatma->title}\" بحمد الله! بارك الله في الجميع.",
                '🎊',
                ['khatma_id' => $khatma->id]
            );
        }
    }

    /**
     * إشعار عند الوصول لـ Streak معين
     */
    public static function onStreakMilestone(User $user, int $streak): void
    {
        $milestones = [7, 30, 100, 365];
        if (!in_array($streak, $milestones)) return;

        // تجنب الإرسال مرتين
        if (self::alreadySentToday($user->id, "StreakMilestone_{$streak}")) return;

        self::create(
            $user->id,
            "StreakMilestone_{$streak}",
            '🔥 إنجاز رائع',
            match($streak) {
                7   => "مبروك! أكملت 7 أيام متتالية من القراءة.",
                30  => "رائع! 30 يوماً متتالياً من المواظبة على القراءة.",
                100 => "إنجاز استثنائي! 100 يوم متتالية من التواصل مع القرآن.",
                365 => "سنة كاملة من الالتزام! أنت قدوة في عائلتنا القرآنية.",
                default => "تهانينا على {$streak} يوماً متتالياً!",
            },
            '🏆',
            ['streak' => $streak]
        );
    }

    /**
     * تذكير الورد اليومي
     */
    public static function sendWardReminder(int $userId, int $daysMissed): void
    {
        $typeMap = [1 => 'WardReminder24h', 2 => 'WardReminder48h', 7 => 'WardReminderWeek'];
        $type    = $typeMap[$daysMissed] ?? null;
        if (!$type) return;

        if (self::alreadySentToday($userId, $type)) return;

        $messages = [
            1 => ['⏰ تذكير بورد القرآن', 'مضى يوم ولم تسجّل ورداً. استثمر دقائق في تلاوة كتاب الله.'],
            2 => ['⚠️ يومان بدون ورد', 'قد تنكسر سلسلة أيامك! عُد الآن وسجّل وردك.'],
            7 => ['💔 أسبوع بدون قرآن', 'اشتاق القرآن إليك! ابدأ من جديد اليوم.'],
        ];

        [$title, $message] = $messages[$daysMissed];
        self::create($userId, $type, $title, $message, '🌙');
    }

    /**
     * تذكير المراجعة
     */
    public static function sendRevisionReminder(int $userId, int $count): void
    {
        if (self::alreadySentToday($userId, 'RevisionReminder')) return;

        self::create(
            $userId,
            'RevisionReminder',
            '🧠 لديك مراجعات اليوم',
            "لديك {$count} " . ($count === 1 ? 'مراجعة' : 'مراجعات') . " مجدولة اليوم.",
            '🔄'
        );
    }
   public static function onJuzCompleted(Khatma $khatma, int $juz, User $user): void
    {
        // 1. إشعار القارئ نفسه بنجاح الإتمام وثبات الأجر
        self::create(
            $user->id,
            'JuzCompletedSelf',
            '📖 تقبل الله منك',
            "هنيئاً لك، لقد أتممت قراءة الجزء {$juz} بنجاح في ختمة: \"{$khatma->title}\".",
            '🎉',
            ['khatma_id' => $khatma->id, 'juz_number' => $juz]
        );

        // 2. [اختياري] إشعار منشئ الختمة (إذا كان شخصاً آخر) ليعرف تقدم الختمة
        if ($khatma->created_by !== $user->id) {
            self::create(
                $khatma->created_by,
                'JuzCompletedCreator',
                '⚡ تقدم في الختمة',
                "أتم القارئ {$user->name} قراءة الجزء {$juz} في ختمتك: \"{$khatma->title}\".",
                '📈',
                ['khatma_id' => $khatma->id, 'juz_number' => $juz, 'reader_id' => $user->id]
            );
        }
    }
}