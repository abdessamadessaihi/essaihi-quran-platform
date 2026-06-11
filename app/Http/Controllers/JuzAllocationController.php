<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JuzAllocationController extends Controller
{
    public function claim(Request $request, \App\Models\Khatma $khatma, $juzNumber)
    {
        $allocation = $khatma->juzAllocations()->where('juz_number', $juzNumber)->firstOrFail();
        
        if ($allocation->status !== \App\Models\JuzAllocation::STATUS_AVAILABLE) {
            return back()->with('error', 'عذراً، هذا الجزء غير متاح للحجز.');
        }

        $allocation->update([
            'user_id'    => $request->user()->id,
            'status'     => \App\Models\JuzAllocation::STATUS_RESERVED,
            'claimed_at' => now(),
        ]);

        return back()->with('success', 'تم حجز الجزء بنجاح 🎉');
    }

    public function start(Request $request, \App\Models\Khatma $khatma, $juzNumber)
    {
        $allocation = $khatma->juzAllocations()->where('juz_number', $juzNumber)->firstOrFail();
        
        if ($allocation->user_id !== $request->user()->id) {
            return back()->with('error', 'لا تملك صلاحية بدء قراءة هذا الجزء.');
        }

        $allocation->update([
            'status'     => \App\Models\JuzAllocation::STATUS_READING,
            'started_at' => now(),
        ]);

        return back()->with('success', 'تم بدء القراءة، تقبل الله منا ومنكم 📖');
    }

    public function complete(Request $request, \App\Models\Khatma $khatma, $juzNumber)
    {
        $allocation = $khatma->juzAllocations()->where('juz_number', $juzNumber)->firstOrFail();
        
        if ($allocation->user_id !== $request->user()->id) {
            return back()->with('error', 'لا تملك صلاحية تأكيد قراءة هذا الجزء.');
        }

        $allocation->markAsCompleted();

        return back()->with('success', 'أحسنت! اكتملت قراءة الجزء، تقبل الله سعيك 🎊');
    }
}