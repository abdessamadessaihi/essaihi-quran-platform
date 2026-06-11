<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\XpTransaction;
use App\Models\Memorization;
use App\Models\DailyWard;
use App\Models\Streak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', 'alltime');
        if (!in_array($period, ['weekly', 'monthly', 'alltime'])) {
            $period = 'alltime';
        }

        $category = $request->input('category', 'xp');
        if (!in_array($category, ['xp', 'streak', 'memorization', 'reading'])) {
            $category = 'xp';
        }

        // Base date ranges
        $dateLimit = null;
        $dateTimeLimit = null;
        if ($period === 'weekly') {
            $dateLimit = Carbon::now()->startOfWeek()->toDateString();
            $dateTimeLimit = Carbon::now()->startOfWeek()->toDateTimeString();
        } elseif ($period === 'monthly') {
            $dateLimit = Carbon::now()->startOfMonth()->toDateString();
            $dateTimeLimit = Carbon::now()->startOfMonth()->toDateTimeString();
        }

        $usersQuery = User::where('is_active', true);

        if ($category === 'xp') {
            $usersQuery->select('users.*')
                ->selectSub(function ($q) use ($period, $dateTimeLimit) {
                    $q->selectRaw('COALESCE(SUM(points), 0)')
                      ->from('xp_transactions')
                      ->whereColumn('xp_transactions.user_id', 'users.id');
                    if ($period !== 'alltime' && $dateTimeLimit) {
                        $q->where('created_at', '>=', $dateTimeLimit);
                    }
                }, 'score')
                ->orderByDesc('score')
                ->orderBy('name');
        } elseif ($category === 'streak') {
            // Streaks are all-time/current
            $usersQuery->select('users.*')
                ->selectSub(function ($q) {
                    $q->selectRaw('COALESCE(current_streak, 0)')
                      ->from('streaks')
                      ->whereColumn('streaks.user_id', 'users.id')
                      ->limit(1);
                }, 'score')
                ->orderByDesc('score')
                ->orderBy('name');
        } elseif ($category === 'memorization') {
            $usersQuery->select('users.*')
                ->selectSub(function ($q) use ($period, $dateLimit) {
                    $q->selectRaw('COALESCE(SUM(ayah_to - ayah_from + 1), 0)')
                      ->from('memorizations')
                      ->whereColumn('memorizations.user_id', 'users.id');
                    if ($period !== 'alltime' && $dateLimit) {
                        $q->where('memorized_at', '>=', $dateLimit);
                    }
                }, 'score')
                ->orderByDesc('score')
                ->orderBy('name');
        } elseif ($category === 'reading') {
            $usersQuery->select('users.*')
                ->selectSub(function ($q) use ($period, $dateLimit) {
                    $q->selectRaw('COUNT(*)')
                      ->from('daily_wards')
                      ->whereColumn('daily_wards.user_id', 'users.id')
                      ->where('is_completed', true);
                    if ($period !== 'alltime' && $dateLimit) {
                        $q->where('ward_date', '>=', $dateLimit);
                    }
                }, 'score')
                ->orderByDesc('score')
                ->orderBy('name');
        }

        $leaderboard = $usersQuery->limit(50)->get();

        // Find current user's rank
        $currentUserRank = null;
        $currentUserScore = 0;
        $allRankedUsers = $usersQuery->limit(500)->get();
        
        foreach ($allRankedUsers as $index => $u) {
            if ($u->id === Auth::id()) {
                $currentUserRank = $index + 1;
                $currentUserScore = (int) ($u->score ?? 0);
                break;
            }
        }

        return view('leaderboard.index', compact(
            'leaderboard',
            'period',
            'category',
            'currentUserRank',
            'currentUserScore'
        ));
    }
}