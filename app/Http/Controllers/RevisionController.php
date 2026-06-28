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
     * جدولة المراجعة التالية بنظام التكرار المتباعد المطور والمحمي
     * اليوم ← غداً ← 3 أيام ← أسبوع ← شهر
     */
    private function scheduleNextRevision(Revision $revision, int $score): void
    {
        $currentType = $revision->revision_type;
        $nextType = $currentType; // افتراضياً تبقى نفس النوع لتثبيتها
        $daysUntilNext = 7;

        // 1. منطق التدرج المرن والتكرار بناءً على النوع والدرجة
        if ($currentType === 'daily') {
            if ($score >= 85) {
                $nextType = 'weekly';
                $daysUntilNext = 7; // إتقان جيد، ترفع لأسبوعية بعد 7 أيام
            } else {
                $nextType = 'daily';
                $daysUntilNext = 1; // يحتاج إعادة مراجعة غداً
            }
        } elseif ($currentType === 'weekly') {
            if ($score >= 92) {
                $nextType = 'monthly';
                $daysUntilNext = 30; // إتقان ممتاز جداً، ترفع لشهرية بعد شهر
            } elseif ($score >= 70) {
                $nextType = 'weekly';
                $daysUntilNext = 7; // يستمر بالمراجعة الأسبوعية العادية كل 7 أيام
            } else {
                $nextType = 'weekly';
                $daysUntilNext = 3; // إتقان مهزوز، تقريب الموعد القادم بعد 3 أيام فقط لتثبيته
            }
        } elseif ($currentType === 'monthly') {
            if ($score >= 80) {
                $nextType = 'monthly';
                $daysUntilNext = 30; // الحفاظ على التكرار الشهري الدائم للمراجعة المستمرة
            } else {
                $nextType = 'weekly'; // النزول للمستوى الأسبوعي مرة أخرى لضعف الدرجة
                $daysUntilNext = 7;
            }
        }

        // حساب التاريخ المستهدف الدقيق
        $targetDate = today()->addDays($daysUntilNext);

        // 2. 🛡️ درع الحماية لمنع التكرار في نفس اليوم المستهدف
        // التحقق مما إذا كانت هناك مراجعة مجدولة مستقبلاً لنفس الحفظ في هذا التاريخ أو قبله لتفادي التراكم
        $alreadyScheduled = Revision::where('user_id', Auth::id())
            ->where('memorization_id', $revision->memorization_id)
            ->where('status', 'pending')
            ->whereDate('scheduled_date', $targetDate->toDateString())
            ->exists();

        // إذا لم تكن مجدولة مسبقاً، ننشئها بأمان
        if (!$alreadyScheduled) {
            Revision::create([
                'user_id'         => Auth::id(),
                'memorization_id' => $revision->memorization_id,
                'revision_type'   => $nextType,
                'status'          => 'pending',
                'scheduled_date'  => $targetDate,
            ]);
        }
    }
}