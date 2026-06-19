<?php

namespace App\Http\Controllers;

use App\Models\Memorization;
use App\Models\DailyWard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Revision;
use App\Models\XpTransaction;

class MemorizationController extends Controller
{
    // ─── Index ────────────────────────────────────────────
    public function index()
    {
        $user = Auth::user();

        $memorizations = Memorization::where('user_id', $user->id)
            ->orderByDesc('memorized_at')
            ->paginate(15);

        // Stats
        $allMems = Memorization::where('user_id', $user->id)->get();

        $totalAyahs = $allMems->sum(fn($m) => ($m->ayah_to - $m->ayah_from) + 1);

        $totalSurahs = $allMems->pluck('surah_number')->unique()->count();

        $excellentCount = $allMems->where('mastery_level', 'excellent')->count();

        $pendingReviewCount = Memorization::where('user_id', $user->id)
            ->whereHas('pendingRevisions')
            ->count();

        // Mastery distribution
        $masteryDistribution = Memorization::where('user_id', $user->id)
            ->selectRaw('mastery_level, count(*) as count')
            ->groupBy('mastery_level')
            ->pluck('count', 'mastery_level');

        $totalMemorizations = $memorizations->total();

        return view('memorizations.index', compact(
            'memorizations',
            'totalAyahs',
            'totalSurahs',
            'excellentCount',
            'pendingReviewCount',
            'masteryDistribution',
            'totalMemorizations'
        ));
    }

    // ─── Create ───────────────────────────────────────────
    public function create()
    {
        $surahs = DailyWard::SURAHS;
        return view('memorizations.create', compact('surahs'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'surah_number'  => 'required|integer|between:1,114',
        'ayah_from'     => 'required|integer|min:1',
        'ayah_to'       => 'required|integer|min:1|gte:ayah_from',
        'mastery_level' => 'required|in:weak,fair,good,excellent',
        'memorized_at'  => 'required|date|before_or_equal:today',
    ], [
        'surah_number.required'        => 'اختر السورة',
        'surah_number.between'         => 'رقم السورة يجب أن يكون بين 1 و 114',
        'ayah_from.required'           => 'حدد رقم الآية البداية',
        'ayah_to.required'             => 'حدد رقم الآية النهاية',
        'ayah_to.gte'                  => 'آية النهاية يجب أن تكون أكبر من أو تساوي آية البداية',
        'mastery_level.required'       => 'حدد مستوى الإتقان',
        'mastery_level.in'             => 'مستوى الإتقان غير صالح',
        'memorized_at.required'        => 'حدد تاريخ الحفظ',
        'memorized_at.before_or_equal' => 'تاريخ الحفظ لا يمكن أن يكون في المستقبل',
    ]);

    // إنشاء سجل الحفظ
    $memorization = Memorization::create([
        'user_id'          => Auth::id(),
        'surah_number'     => (int) $validated['surah_number'],
        'ayah_from'        => (int) $validated['ayah_from'],
        'ayah_to'          => (int) $validated['ayah_to'],
        'mastery_level'    => $validated['mastery_level'],
        'memorized_at'     => $validated['memorized_at'],
        'last_reviewed_at' => null,
        'review_score'     => 0,
    ]);

    // مراجعة اليوم
    Revision::create([
        'user_id'         => Auth::id(),
        'memorization_id' => $memorization->id,
        'revision_type'   => Revision::TYPE_DAILY,
        'status'          => Revision::STATUS_PENDING,
        'scheduled_date'  => today(),
    ]);

    // مراجعة غداً
    Revision::create([
        'user_id'         => Auth::id(),
        'memorization_id' => $memorization->id,
        'revision_type'   => Revision::TYPE_DAILY,
        'status'          => Revision::STATUS_PENDING,
        'scheduled_date'  => today()->addDay(),
    ]);

    // بعد 3 أيام
    Revision::create([
        'user_id'         => Auth::id(),
        'memorization_id' => $memorization->id,
        'revision_type'   => Revision::TYPE_WEEKLY,
        'status'          => Revision::STATUS_PENDING,
        'scheduled_date'  => today()->addDays(3),
    ]);

    // بعد أسبوع
    Revision::create([
        'user_id'         => Auth::id(),
        'memorization_id' => $memorization->id,
        'revision_type'   => Revision::TYPE_WEEKLY,
        'status'          => Revision::STATUS_PENDING,
        'scheduled_date'  => today()->addWeek(),
    ]);

    // بعد شهر
    Revision::create([
        'user_id'         => Auth::id(),
        'memorization_id' => $memorization->id,
        'revision_type'   => Revision::TYPE_MONTHLY,
        'status'          => Revision::STATUS_PENDING,
        'scheduled_date'  => today()->addMonth(),
    ]);

    // XP
    $ayahCount = ($memorization->ayah_to - $memorization->ayah_from) + 1;

    XpTransaction::award(
        Auth::id(),
        $ayahCount * 10,
        XpTransaction::SOURCE_MEMORIZATION,
        $memorization->id,
        "حفظ {$ayahCount} آية"
    );

    return redirect()
        ->route('memorizations.index')
        ->with('success', 'تم حفظ السجل بنجاح، بارك الله في جهدك 🎉');
}
    // ─── Edit ─────────────────────────────────────────────
    public function edit(Memorization $memorization)
    {
        if ($memorization->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا السجل');
        }
        $surahs = DailyWard::SURAHS;
        return view('memorizations.edit', compact('memorization', 'surahs'));
    }

    // ─── Update ───────────────────────────────────────────
    public function update(Request $request, Memorization $memorization)
    {
        if ($memorization->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذا السجل');
        }

        $validated = $request->validate([
            'mastery_level'    => 'required|in:weak,fair,good,excellent',
            'last_reviewed_at' => 'nullable|date|before_or_equal:today',
            'review_score'     => 'nullable|integer|between:0,100',
        ]);

        $memorization->update($validated);

        return redirect()->route('memorizations.index')
            ->with('success', 'تم تحديث سجل الحفظ بنجاح');
    }
    

    // ─── Destroy ──────────────────────────────────────────
    public function destroy(Memorization $memorization)
    {
        if ($memorization->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بحذف هذا السجل');
        }
        $memorization->delete();

        return back()->with('success', 'تم حذف السجل بنجاح');
    }
}