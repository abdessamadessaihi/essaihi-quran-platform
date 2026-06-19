<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Revision;
use App\Models\Notification;
use Illuminate\Console\Command;
use App\Services\NotificationService;

class SendRevisionReminders extends Command
{
    protected $signature   = 'notify:revision-reminders';
    protected $description = 'إرسال تذكيرات المراجعة';

    public function handle(): void
    {
        // المراجعات المستحقة اليوم
        $dueRevisions = Revision::where('status', 'pending')
            ->whereDate('scheduled_date', today())
            ->with(['user', 'memorization'])
            ->get()
            ->groupBy('user_id');

        foreach ($dueRevisions as $userId => $revisions) {
            $user  = $revisions->first()->user;
            $count = $revisions->count();

            if (!$user?->is_active) continue;

            $exists = Notification::where('user_id', $userId)
                ->where('type', 'RevisionReminder')
                ->whereDate('created_at', today())
                ->exists();

            if ($exists) continue;

            Notification::create([
                'user_id' => $userId,
                'type'    => 'RevisionReminder',
                'data'    => [
                    'title'   => '🧠 لديك مراجعات اليوم',
                    'message' => "لديك {$count} " . ($count === 1 ? 'مراجعة' : 'مراجعات') . " مجدولة اليوم. المواظبة على المراجعة تثبّت الحفظ.",
                    'icon'    => '🔄',
                    'count'   => $count,
                ],
                'channel' => 'database',
                'is_read' => false,
                'sent_at' => now(),
            ]);
        }

        // المراجعات المتأخرة (فائتة)
        $overdueRevisions = Revision::where('status', 'pending')
            ->whereDate('scheduled_date', '<', today())
            ->with(['user'])
            ->get()
            ->groupBy('user_id');

        foreach ($overdueRevisions as $userId => $revisions) {
            $user  = $revisions->first()->user;
            $count = $revisions->count();

            if (!$user?->is_active) continue;

            $exists = Notification::where('user_id', $userId)
                ->where('type', 'RevisionOverdue')
                ->whereDate('created_at', today())
                ->exists();

            if ($exists) continue;

            Notification::create([
                'user_id' => $userId,
                'type'    => 'RevisionOverdue',
                'data'    => [
                    'title'   => '⚠️ مراجعات فائتة',
                    'message' => "لديك {$count} مراجعة فائتة! الحفظ بلا مراجعة كالماء بلا إناء.",
                    'icon'    => '⚠️',
                    'count'   => $count,
                ],
                'channel' => 'database',
                'is_read' => false,
                'sent_at' => now(),
            ]);
        }

        $this->info("✅ تم إرسال تذكيرات المراجعة");
    }
}