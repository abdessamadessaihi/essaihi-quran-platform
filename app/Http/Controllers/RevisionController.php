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

        // مراجعات اليوم المتبقية (المعلقة والمتأخرة المجدولة لليوم أو ما قبله)
        $todayRevisions = Revision::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'overdue'])
            ->whereDate('scheduled_date', '<=', today())
            ->with('memorization')
            ->orderBy('scheduled_date')
            ->get();

        // مراجعات متأخرة (فائتة) كعدد للإحصائيات
        $overdueRevisions = Revision::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '<', today())
            ->count();

        // مراجعات هذا الأسبوع
        $weekRevisions = Revision::where('user_id', $user->id)
            ->whereBetween('scheduled_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count();

        // مكتملة هذا الأسبوع
        $completedThisWeek = Revision::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereBetween('completed_date', [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])->count();

        $achievementRate = $weekRevisions > 0
            ? round(($completedThisWeek / $weekRevisions) * 100)
            : 0;

        $totalMemorizations = Memorization::where('user_id', $user->id)->count();

        // 🌟 الجديد: جلب مسار المراجعات الكامل (تمت، لم تتم، مجدولة مستقبلاً)
        $allRevisionsLog = Revision::where('user_id', $user->id)
            ->with('memorization')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END") // المراجعات القادمة أولاً ثم المكتملة
            ->orderByDesc('scheduled_date') // الترتيب حسب التاريخ
            ->paginate(10, ['*'], 'revisions_page'); // استخدام اسم مخصص للـ pagination لمنع التداخل

        return view('revisions.index', compact(
            'todayRevisions', 'weekRevisions', 'completedThisWeek',
            'achievementRate', 'totalMemorizations', 'overdueRevisions',
            'allRevisionsLog' // تدوير المتغير الجديد للملف
        ));
    }

    public function complete(Request $request, Revision $revision)
    {
        abort_unless($revision->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'score' => 'nullable|integer|min:0|max:100',
            'notes' => 'nullable|string|max:300',
        ]);

        $score = $validated['score'] ?? 80;
        $revision->complete($score, $validated['notes'] ?? null);

        // تحديث مستوى الإتقان بناءً على درجة المراجعة
        $mastery = match(true) {
            $score >= 90 => 'excellent',
            $score >= 70 => 'good',
            $score >= 50 => 'fair',
            default      => 'weak',
        };
        $revision->memorization->update([
            'mastery_level'    => $mastery,
            'last_reviewed_at' => today(),
            'review_score'     => $score,
        ]);

        // جدولة المراجعة التالية بنظام التكرار المتباعد
        $this->scheduleNextRevision($revision, $score);

        // منح XP
        XpTransaction::award(
            Auth::id(), 50,
            'revision',
            $revision->id,
            'مراجعة مكتملة'
        );

        return back()->with('success', 'أحسنت! تمت المراجعة بنجاح ✅');
    }

    public function skip(Revision $revision)
    {
        abort_unless($revision->user_id === Auth::id(), 403);
        $revision->update(['status' => 'skipped']);
        return back()->with('success', 'تم تأجيل المراجعة');
    }

    /**
     * جدولة المراجعة التالية بنظام التكرار المتباعد
     * اليوم → غداً → 3 أيام → أسبوع → شهر
     */
    private function scheduleNextRevision(Revision $revision, int $score): void
    {
        // خريطة التكرار المتباعد حسب نوع المراجعة
        $nextType = match($revision->revision_type) {
            'daily'   => 'weekly',
            'weekly'  => 'monthly',
            'monthly' => null, // اكتملت دورة المراجعة
            default   => null,
        };

        if (!$nextType) return;

        // كلما كان الإتقان ضعيفاً، كلما كانت المراجعة التالية أقرب
        $daysUntilNext = match(true) {
            $score >= 90 && $nextType === 'weekly'  => 7,
            $score >= 90 && $nextType === 'monthly' => 30,
            $score >= 70 && $nextType === 'weekly'  => 5,
            $score >= 70 && $nextType === 'monthly' => 21,
            $score >= 50 && $nextType === 'weekly'  => 3,
            $score >= 50 && $nextType === 'monthly' => 14,
            $nextType === 'weekly'                  => 2,
            default                                 => 7,
        };

        Revision::create([
            'user_id'         => Auth::id(),
            'memorization_id' => $revision->memorization_id,
            'revision_type'   => $nextType,
            'status'          => 'pending',
            'scheduled_date'  => today()->addDays($daysUntilNext),
        ]);
    }
}