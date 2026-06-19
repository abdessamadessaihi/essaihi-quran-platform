<?php

namespace App\Http\Controllers;

use App\Models\JuzAllocation;
use App\Models\Khatma;
use App\Models\XpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Services\NotificationService;

class JuzAllocationController extends Controller
{
    public function claim(Request $request, Khatma $khatma, int $juz)
    {
        // Mutex — منع الحجز المزدوج
        $allocation = DB::transaction(function () use ($khatma, $juz) {
            $alloc = JuzAllocation::where('khatma_id', $khatma->id)
                ->where('juz_number', $juz)
                ->lockForUpdate()
                ->firstOrFail();

            if ($alloc->status !== 'available') {
                return null;
            }

            $alloc->update([
                'user_id'    => Auth::id(),
                'status'     => 'reserved',
                'claimed_at' => now(),
                'deadline_at'=> now()->addDays(7),
            ]);

            return $alloc;
        });

        if (!$allocation) {
            return back()->with('error', 'هذا الجزء محجوز بالفعل، اختر جزءاً آخر.');
        }

        return back()->with('success', "تم حجز الجزء {$juz} بنجاح 🎉");
    }

    public function start(Request $request, Khatma $khatma, int $juz)
    {
        $alloc = JuzAllocation::where('khatma_id', $khatma->id)
            ->where('juz_number', $juz)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $alloc->update([
            'status'     => 'reading',
            'started_at' => now(),
        ]);

        return back()->with('success', "بارك الله فيك! بدأت قراءة الجزء {$juz} 📖");
    }

    public function complete(Request $request, Khatma $khatma, int $juz)
    {
        $alloc = JuzAllocation::where('khatma_id', $khatma->id)
            ->where('juz_number', $juz)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $alloc->markAsCompleted();

        // منح XP
        XpTransaction::award(
            Auth::id(), 100,
            XpTransaction::SOURCE_JUZ,
            $alloc->id,
            "إكمال الجزء {$juz} من ختمة: {$khatma->title}"
        );
        // تحديث عدد الأجزاء المكتملة في الختمة$khatma->refresh();
            NotificationService::onJuzCompleted($khatma, $juz, auth()->user());
        return back()->with('success', "تقبل الله منكم. اكتمل الجزء {$juz} بحمد الله 🎉");
    }
}