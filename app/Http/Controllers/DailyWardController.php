<?php

namespace App\Http\Controllers;

use App\Models\DailyWard;
use App\Models\Khatma;
use App\Models\Streak;
use App\Models\XpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NotificationService;


class DailyWardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ورد اليوم
        $wardToday = DailyWard::where('user_id', $user->id)
                               ->whereDate('ward_date', today())
                               ->first();

        // سجل الأوراد
        $allWards = DailyWard::where('user_id', $user->id)
                              ->with('khatma')
                              ->orderByDesc('ward_date')
                              ->paginate(10);

        // الختمات النشطة للربط
        $khatmas = Khatma::where('status', 'active')
                         ->where(function ($q) use ($user) {
                             $q->where('created_by', $user->id)
                               ->orWhereHas('family.activeMembers', fn($q2) =>
                                   $q2->where('users.id', $user->id)
                               );
                         })->get();

        // streak المستخدم
        $streak = Streak::firstOrCreate(
            ['user_id' => $user->id],
            ['current_streak' => 0, 'longest_streak' => 0, 'total_active_days' => 0]
        );

        return view('ward.index', compact(
            'wardToday', 'allWards', 'khatmas', 'streak'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // منع التكرار
        $exists = DailyWard::where('user_id', $user->id)
                            ->whereDate('ward_date', $request->ward_date)
                            ->exists();

        if ($exists) {
            return back()->with('error', 'لديك ورد مسجّل لهذا اليوم بالفعل.');
        }

        $validated = $request->validate([
            'target_unit'  => 'required|in:pages,hizbs,juz,ayahs',
            'target_value' => 'required|numeric|min:0.01',
            'ward_date'    => 'required|date',
            'khatma_id'    => 'nullable|exists:khatmas,id',
            'location_type'=> 'nullable|in:page,surah,hizb,juz',
            'start_page'   => 'nullable|integer|min:1|max:604',
            'end_page'     => 'nullable|integer|min:1|max:604',
            'start_surah'  => 'nullable|integer|min:1|max:114',
            'end_surah'    => 'nullable|integer|min:1|max:114',
            'start_hizb'   => 'nullable|integer|min:1|max:60',
            'end_hizb'     => 'nullable|integer|min:1|max:60',
            'start_juz'    => 'nullable|integer|min:1|max:30',
            'end_juz'      => 'nullable|integer|min:1|max:30',
            'notes'        => 'nullable|string|max:500',
        ]);

        DailyWard::create([
            ...$validated,
            'user_id'      => $user->id,
            'actual_value' => 0,
            'adherence_pct'=> 0,
            'is_completed' => false,
        ]);

        return back()->with('success', 'تم حفظ ورد اليوم بنجاح 🌙');
    }

    public function update(Request $request, DailyWard $ward)
    {
        abort_unless($ward->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'actual_value' => 'required|numeric|min:0',
        ]);

        $ward->complete((float) $validated['actual_value']);

        return back()->with('success', 'تم تحديث الورد ✅');
    }
public function complete(Request $request)
{
    $user = Auth::user();

    $ward = DailyWard::where('user_id', $user->id)
                      ->whereDate('ward_date', today())
                      ->firstOrFail();

    if ($ward->is_completed) {
        return back()->with('error', 'الورد مكتمل بالفعل بحمد الله.');
    }

    $ward->complete((float) $ward->target_value);

    // تحديث الـ Streak وجلب الكائن الصحيح
    $streak = Streak::firstOrCreate(['user_id' => $user->id]);
    $streak->recordActivity();
    
    // 🌟 الإصلاح هنا: استدعاء المتغيرات من الكائنات الصحيحة ($streak و $user)
    $milestones = [7, 30, 100, 365];
    if (in_array($streak->current_streak, $milestones)) {
        NotificationService::onStreakMilestone($user, $streak->current_streak);
    }

    // منح XP
    XpTransaction::award(
        $user->id, 50,
        XpTransaction::SOURCE_WARD,
        $ward->id,
        'إكمال الورد اليومي'
    );

    return back()->with('success', 'أحسنت! اكتمل ورد اليوم بحمد الله 🎉');
}

    public function destroy(DailyWard $ward)
    {
        abort_unless($ward->user_id === Auth::id(), 403);
        $ward->delete();
        return back()->with('success', 'تم حذف السجل');
    }
}