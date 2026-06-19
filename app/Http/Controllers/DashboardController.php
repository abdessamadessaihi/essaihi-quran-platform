<?php

namespace App\Http\Controllers;

use App\Models\DailyWard;
use App\Models\Khatma;
use App\Models\Memorization;
use App\Models\Streak;
use App\Models\XpTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // 1. إدارة السلسلة (Streak)
        $streak = Streak::firstOrCreate(
            ['user_id' => $user->id],
            ['current_streak' => 0, 'longest_streak' => 0, 'total_active_days' => 0]
        );
        $streak->checkAndResetIfNeeded();

        // 2. ورد اليوم
        $todayWard = DailyWard::where('user_id', $user->id)
                               ->whereDate('ward_date', today())
                               ->first();

        // 3. الختمات النشطة (مع جلب المرسل أو العلاقات إذا لزم الأمر لتسريع الأداء)
        $activeKhatmas = Khatma::where('status', 'active')
            ->where(function($q) use ($user) {
                $q->where('created_by', $user->id)
                  ->orWhere('type', 'platform');
            })
            ->take(3)
            ->get();

        // 4. حساب إجمالي الآيات المحفوظة مباشرة من قاعدة البيانات (تحسين هائل للأداء)
        $totalAyahs = Memorization::where('user_id', $user->id)
            ->select(DB::raw('SUM(ayah_to - ayah_from + 1) as total'))
            ->value('total') ?? 0;

        // تقدير الأجزاء المحفوظة (بناءً على معدل 200 آية للجزء تقريباً)
        $memorizedJuzEstimate = $totalAyahs > 0 ? round($totalAyahs / 200, 1) : 0;

        // 5. حساب إجمالي نقاط الـ XP
        $totalXp = XpTransaction::where('user_id', $user->id)->sum('points');
        $level = $this->calculateLevel((int)$totalXp);

        // 6. تجميع الإحصائيات للوحة التحكم
        $dashboardStats = [
            'current_streak'         => $streak->current_streak,
            'longest_streak'         => $streak->longest_streak,
            'completed_wards_count'  => DailyWard::where('user_id', $user->id)->where('is_completed', true)->count(),
            'memorized_juz_estimate' => $memorizedJuzEstimate,
            'total_xp'               => $totalXp,
            'level'                  => $level,
        ];

        $topUsers = User::where('is_active', true)
            ->get()
            ->map(function ($u) {
                $u->total_xp = (int) XpTransaction::where('user_id', $u->id)->sum('points');
                return $u;
            })
            ->sortByDesc('total_xp')
            ->take(3)
            ->values();

        return view('dashboard', compact(
            'todayWard', 'activeKhatmas', 'dashboardStats', 'streak', 'topUsers'
        ));
    }

    /**
     * حساب مستوى المستخدم بناءً على نقاط الخبرة.
     */
    private function calculateLevel(int $xp): string
    {
        return match(true) {
            $xp >= 5000 => '🌟 حافظ القرآن',
            $xp >= 2000 => '🎖️ قارئ متميز',
            $xp >= 1000 => '📖 قارئ نشط',
            $xp >= 500  => '🌱 مبتدئ متحمس',
            default     => '🌙 مبتدئ',
        };
    }
}