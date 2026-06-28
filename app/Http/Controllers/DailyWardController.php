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

    // منع التكرار لنفس اليوم
    $exists = DailyWard::where('user_id', $user->id)
                        ->whereDate('ward_date', $request->ward_date)
                        ->exists();

    if ($exists) {
        return back()->with('error', 'لديك ورد مسجّل لهذا اليوم بالفعل.')->withInput();
    }

    // 1. التحقق من المدخلات بناءً على القيم المرسلة من الفورم (بصيغة الجمع)
    $request->validate([
        'target_unit'   => 'required|in:pages,surahs,hizbs,juzs,ayahs',
        'ward_date'     => 'required|date',
        'khatma_id'     => 'nullable|exists:khatmas,id',
        'notes'         => 'nullable|string|max:500',
        // قواعد التحقق المشروطة للحقول المخصصة لـ الـ Form
        'start_page'    => 'required_if:target_unit,pages|nullable|integer|min:1|max:604',
        'end_page'      => 'required_if:target_unit,pages|nullable|integer|min:1|max:604',
        'specific_surah'=> 'required_if:target_unit,surahs|nullable|integer|min:1|max:114',
        'specific_hizb' => 'required_if:target_unit,hizbs|nullable|integer|min:1|max:60',
        'specific_juz'  => 'required_if:target_unit,juzs|nullable|integer|min:1|max:30',
        'ayahs_count'   => 'required_if:target_unit,ayahs|nullable|integer|min:1',
    ]);

    $targetUnit = $request->target_unit;
    
    // تجهيز المصفوفة الأساسية للإدخال
    $insertData = [
        'user_id'       => $user->id,
        'ward_date'     => $request->ward_date,
        'khatma_id'     => $request->khatma_id,
        'notes'         => $request->notes,
        'target_unit'   => $targetUnit, // سيأخذ القيمة بالجمع (pages, surahs...) لتطابق الـ ENUM تماماً
        'actual_value'  => 0,
        'adherence_pct' => 0,
        'is_completed'  => false,
    ];

    // 2. توزيع الحقول الفرعية وحساب حجم المستهدف (target_value) بشكل صحيح لمنع الخلط
    if ($targetUnit === 'pages') {
        $insertData['location_type'] = 'page';
        $insertData['start_page']    = $request->start_page;
        $insertData['end_page']      = $request->end_page;
        $insertData['target_value']  = abs($request->end_page - $request->start_page) + 1;

    } elseif ($targetUnit === 'surahs') {
        $insertData['location_type'] = 'surah';
        $insertData['start_surah']   = $request->specific_surah;
        $insertData['end_surah']     = $request->specific_surah;
        $insertData['target_value']  = 1; // الورد هنا هو سورة واحدة

    } elseif ($targetUnit === 'hizbs') {
        $insertData['location_type'] = 'hizb';
        $insertData['start_hizb']    = $request->specific_hizb;
        $insertData['end_hizb']      = $request->specific_hizb;
        $insertData['target_value']  = 1;

    } elseif ($targetUnit === 'juzs') {
        $insertData['location_type'] = 'juz';
        $insertData['start_juz']     = $request->specific_juz;
        $insertData['end_juz']       = $request->specific_juz;
        $insertData['target_value']  = 1;

    } elseif ($targetUnit === 'ayahs') {
        $insertData['location_type'] = null; // الآيات الحرة غالباً لا تتبع لنطاق ثابت
        $insertData['target_value']  = $request->ayahs_count ?? 1;
    }

    // 3. الإدخال النهائي في قاعدة البيانات
    DailyWard::create($insertData);

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