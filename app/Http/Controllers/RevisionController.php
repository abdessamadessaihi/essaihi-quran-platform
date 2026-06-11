<?php

namespace App\Http\Controllers;

use App\Models\Revision;
use App\Models\Memorization;
use App\Models\XpTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RevisionController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $todayRevisions = Revision::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '<=', today())
            ->with('memorization')
            ->get();

        $weekRevisions = Revision::where('user_id', $user->id)
            ->whereBetween('scheduled_date', [
                now()->startOfWeek(), now()->endOfWeek()
            ])->count();

        $completedThisWeek = Revision::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('completed_date', [
                now()->startOfWeek(), now()->endOfWeek()
            ])->count();

        $achievementRate = $weekRevisions > 0
            ? round(($completedThisWeek / $weekRevisions) * 100)
            : 0;

        $totalMemorizations = Memorization::where('user_id', $user->id)->count();

        return view('revisions.index', compact(
            'todayRevisions','weekRevisions',
            'completedThisWeek','achievementRate','totalMemorizations'
        ));
    }

    public function complete(Request $request, Revision $revision)
    {
        abort_unless($revision->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'score' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:300',
        ]);

        $revision->complete($validated['score'] ?? 80, $validated['notes'] ?? null);

        // جدولة المراجعة التالية
        $nextDate = match($revision->revision_type) {
            'daily'   => today()->addDays(3),
            'weekly'  => today()->addWeeks(1),
            'monthly' => today()->addMonths(1),
            default   => today()->addDays(7),
        };

        Revision::create([
            'user_id'         => Auth::id(),
            'memorization_id' => $revision->memorization_id,
            'revision_type'   => $revision->revision_type === 'daily' ? 'weekly' : 'monthly',
            'status'          => 'pending',
            'scheduled_date'  => $nextDate,
        ]);

        XpTransaction::award(Auth::id(), 50, 'revision', $revision->id, 'مراجعة مكتملة');

        return back()->with('success', 'أحسنت! تمت المراجعة بنجاح ✅');
    }

    public function skip(Revision $revision)
    {
        abort_unless($revision->user_id === Auth::id(), 403);
        $revision->update(['status' => 'skipped']);
        return back();
    }
}