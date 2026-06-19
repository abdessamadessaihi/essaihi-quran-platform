<?php

// ─────────────────────────────────────────────────────────────────
//  Helpers globaux pour les vues de notifications
// ─────────────────────────────────────────────────────────────────

if (!function_exists('notifIcon')) {
    /**
     * Retourne l'emoji correspondant au type de notification.
     */
    function notifIcon(string $type): string
    {
        return match (true) {
            str_contains($type, 'Ward')         => '🌙',
            str_contains($type, 'Memorization') => '📖',
            str_contains($type, 'Revision')     => '🧠',
            str_contains($type, 'Streak')       => '🔥',
            str_contains($type, 'Badge')        => '🏅',
            str_contains($type, 'Family') || str_contains($type, 'NewMember') => '👨‍👩‍👧',            str_contains($type, 'Khatma')       => '📗',
            str_contains($type, 'XP')           => '✨',
            default                             => '🔔',
        };
    }
}

if (!function_exists('notifIconClass')) {
    /**
     * Retourne la classe CSS pour l'icône selon le type.
     */
    function notifIconClass(string $type): string
    {
        return match (true) {
            str_contains($type, 'Ward')         => 'ward-type',
            str_contains($type, 'Memorization') => 'memo-type',
            str_contains($type, 'Revision')     => 'memo-type',
            str_contains($type, 'Streak')       => 'streak-type',
            str_contains($type, 'Badge')        => 'badge-type',
            str_contains($type, 'Family')       => 'family-type',
            default                             => 'default-type',
        };
    }
}

if (!function_exists('notifTitle')) {
    /**
     * Retourne le titre lisible de la notification.
     */
   function notifTitle(string $type, ?array $data): string
    {
        if (!empty($data['title'])) {
            return $data['title'];
        }

        return match (true) {
            str_contains($type, 'FamilyRequestAccepted') => '✅ تم قبول انضمامك للعائلة',
            str_contains($type, 'FamilyRequestRejected') => '❌ نعتذر، تم رفض طلب الانضمام',
            str_contains($type, 'WardReminder')     => 'تذكير بورد اليوم',
            str_contains($type, 'WardCompleted')    => 'أُتمّ ورد اليوم 🎉',
            str_contains($type, 'MemorizationNew')  => 'حفظ جديد مُسجَّل',
            str_contains($type, 'RevisionDue')      => 'موعد مراجعة قادم',
            str_contains($type, 'RevisionDone')     => 'تمت المراجعة بنجاح',
            str_contains($type, 'StreakMilestone')  => 'إنجاز في سلسلة الأيام 🔥',
            str_contains($type, 'StreakBroken')     => 'انكسرت سلسلتك اليومية',
            str_contains($type, 'BadgeEarned')      => 'وسام جديد 🏅',
            str_contains($type, 'FamilyJoined')     => 'عضو جديد انضم للعائلة',
            str_contains($type, 'KhatmaCompleted')  => 'اكتملت الختمة القرآنية 📗',
            str_contains($type, 'FamilyMemberRemoved') => '🚫 تم إيقاف عضويتك في العائلة',
            
            default                                 => 'إشعار من المنصة',
        };
    }
}

if (!function_exists('notifDesc')) {
    /**
     * Retourne la description de la notification.
     */
    function notifDesc(string $type, ?array $data): string
    {
        if (!empty($data['message'])) {
            return $data['message'];
        }
        if (!empty($data['body'])) {
            return $data['body'];
        }

        return match (true) {
            str_contains($type, 'FamilyRequestAccepted') => 'وافق مسؤول العائلة على طلب انضمامك.',
            str_contains($type, 'FamilyRequestRejected') => 'تم رفض طلب انضمامك إلى العائلة من قِبل مسؤول العائلة.',
            str_contains($type, 'WardReminder')     => 'لا تنسَ أورادك اليومي وحافظ على سلسلتك.',
            str_contains($type, 'WardCompleted')    => 'بارك الله فيك! أتممت ورد اليوم بالكامل.',
            str_contains($type, 'MemorizationNew')  => 'تم تسجيل محفوظاتك الجديدة بنجاح.',
            str_contains($type, 'RevisionDue')      => 'حان موعد مراجعة بعض محفوظاتك لتثبيتها.',
            str_contains($type, 'RevisionDone')     => 'أحسنت! اكتملت جلسة المراجعة.',
            str_contains($type, 'StreakMilestone')  => 'تبارك الله! حققت إنجازاً في السلسلة اليومية.',
            str_contains($type, 'StreakBroken')     => 'فوّت يوماً. لا بأس، ابدأ من جديد اليوم!',
            str_contains($type, 'BadgeEarned')      => 'حصلت على وسام جديد. شكراً على مجهودك!',
            str_contains($type, 'FamilyJoined')     => 'انضم فرد جديد إلى عائلتك القرآنية.',
            str_contains($type, 'KhatmaCompleted')  => 'اكتمل ختم القرآن الكريم. جزاكم الله خيراً!',
            str_contains($type, 'FamilyMemberRemoved') => 'قام مسؤول العائلة بإيقاف عضويتك من المجموعة العائلية القرآنية.',
            default                                 => 'تحقق من المنصة لمعرفة التفاصيل.',
        };
    }
}

if (!function_exists('notifChannel')) {
    /**
     * Retourne le libellé arabe du canal.
     */
    function notifChannel(string $channel): string
    {
        return match ($channel) {
            'database' => '🗄️ داخلي',
            'email'    => '📧 بريد',
            'push'     => '📱 إشعار',
            default    => $channel,
        };
    }
}
