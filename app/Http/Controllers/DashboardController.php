<?php

namespace App\Http\Controllers;

use App\Models\Khatma;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $streak = $user->streak;
        $todayWard = $user->dailyWards()
            ->whereDate('ward_date', today())
            ->first();

        $completedWardsCount = $user->dailyWards()
            ->where('is_completed', true)
            ->count();

        $totalXp = (int) $user->xpTransactions()->sum('points');

        $memorizedAyahs = $user->memorizations()
            ->get(['ayah_from', 'ayah_to'])
            ->sum('ayah_count');

        $memorizedJuzEstimate = (int) floor(($memorizedAyahs / 6236) * 30);

        $activeKhatmas = $user->createdKhatmas()
            ->where('status', Khatma::STATUS_ACTIVE)
            ->latest()
            ->limit(2)
            ->get();

        $dashboardStats = [
            'current_streak' => $streak?->current_streak ?? 0,
            'longest_streak' => $streak?->longest_streak ?? 0,
            'completed_wards_count' => $completedWardsCount,
            'memorized_juz_estimate' => $memorizedJuzEstimate,
            'total_xp' => $totalXp,
            'level' => $totalXp >= 1000 ? 'متقدم' : ($totalXp >= 250 ? 'مجتهد' : 'مبتدئ'),
        ];

        return view('dashboard', compact(
            'user',
            'dashboardStats',
            'todayWard',
            'activeKhatmas'
        ));
    }
}
