<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\DailyWard;
use App\Models\Notification  ;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class SendWardReminders extends Command
{
    protected $signature   = 'notify:ward-reminders';
    protected $description = 'إرسال تذكيرات الورد اليومي';

    public function handle(): void
    {
        $today = today();

        // المستخدمون الذين لم يسجّلوا ورد اليوم
        $usersWithoutWard = User::where('is_active', true)
            ->whereDoesntHave('dailyWards', fn($q) =>
                $q->whereDate('ward_date', $today)
            )
            ->get();

        foreach ($usersWithoutWard as $user) {
            $lastWard = DailyWard::where('user_id', $user->id)
                ->orderByDesc('ward_date')->first();

            if (!$lastWard) continue;

            $daysSinceLast = $lastWard->ward_date->diffInDays($today);

            // إشعار بعد 24 ساعة
            if ($daysSinceLast >= 1) {
                $this->sendIfNotSent($user->id, 'WardReminder24h', [
                    'title'   => '🌙 تذكير بورد القرآن',
                    'message' => 'مضى يوم ولم تسجّل ورداً بعد. استثمر دقائق من وقتك في تلاوة كتاب الله.',
                    'icon'    => '📖',
                    'date'    => $today->toDateString(),
                ]);
            }

            // إشعار بعد 48 ساعة
            if ($daysSinceLast >= 2) {
                $this->sendIfNotSent($user->id, 'WardReminder48h', [
                    'title'   => '⏰ مرّ يومان بدون ورد',
                    'message' => 'انتبه! قد تنكسر سلسلة أيامك المتتالية. عُد الآن وسجّل وردك قبل فوات الأوان.',
                    'icon'    => '⚠️',
                    'date'    => $today->toDateString(),
                ]);
            }

            // إشعار بعد أسبوع
            if ($daysSinceLast >= 7) {
                $this->sendIfNotSent($user->id, 'WardReminderWeek', [
                    'title'   => '💔 أسبوع بدون قرآن',
                    'message' => 'اشتاق القرآن إليك! لا تقطع حبل التواصل مع كتاب الله. ابدأ من جديد اليوم.',
                    'icon'    => '📖',
                    'date'    => $today->toDateString(),
                ]);
            }
        }

        $this->info("✅ تم إرسال تذكيرات الورد");
    }

    private function sendIfNotSent(int $userId, string $type, array $data): void
    {
        // تجنب إرسال نفس الإشعار مرتين في نفس اليوم
        $exists = Notification::where('user_id', $userId)
            ->where('type', $type)
            ->whereDate('created_at', today())
            ->exists();

        if ($exists) return;

        Notification::create([
            'user_id' => $userId,
            'type'    => $type,
            'data'    => $data,
            'channel' => 'database',
            'is_read' => false,
            'sent_at' => now(),
        ]);
    }
}