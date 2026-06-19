<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\NotificationService;

class FamilyController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        // عائلات المستخدم النشطة
        $myFamilies = Family::whereHas('memberships', fn($q) =>
            $q->where('user_id', $user->id)->where('status', 'active')
        )->withCount([
            'memberships as active_members_count' => fn($q) => $q->where('status', 'active'),
            'khatmas as active_khatmas_count' => fn($q) => $q->where('status', 'active'),
            'khatmas as completed_khatmas_count' => fn($q) => $q->where('status', 'completed'),
        ])->get();

        // طلبات الانضمام المعلقة
        $pendingRequest = FamilyMember::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('family')
            ->first();

        // عائلات متاحة للانضمام
        $availableFamilies = Family::where('is_active', true)
            ->whereDoesntHave('memberships', fn($q) =>
                $q->where('user_id', $user->id)->whereIn('status', ['active', 'pending'])
            )->withCount([
                'memberships as active_members_count' => fn($q) => $q->where('status', 'active')
            ])->get();

        return view('families.index', compact('myFamilies', 'pendingRequest', 'availableFamilies'));
    }

    public function create()
    {
        return view('families.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = Auth::user();

        // تحسين توليد الـ Slug ليدعم الحروف العربية بشكل صحيح
        $slugBase = Str::slug($validated['name'], '-', 'ar') ?: 'family';

        $family = Family::create([
            ...$validated,
            'created_by' => $user->id,
            'slug'       => $slugBase . '-' . Str::lower(Str::random(5)),
            'is_active'  => true,
        ]);

        // المنشئ يصبح Admin تلقائياً
        FamilyMember::create([
            'user_id'     => $user->id,
            'family_id'   => $family->id,
            'status'      => 'active',
            'role'        => 'admin',
            'approved_by' => $user->id,
            'joined_at'   => now(),
        ]);

        // ترقية دور المستخدم إن كان عضواً عادياً
        if ($user->role === User::ROLE_MEMBER) {
            $user->update(['role' => User::ROLE_FAMILY_ADMIN]);
        }

        return redirect()->route('families.show', $family)
            ->with('success', 'تم إنشاء العائلة بنجاح 🎉');
    }

    public function show(Family $family)
    {
        /** @var User $user */
        $user = Auth::user();

        // التحقق من العضوية الحالية للمشاهد
        $membership = FamilyMember::where('user_id', $user->id)
            ->where('family_id', $family->id)
            ->first();

        $isAdmin = $membership?->role === 'admin' || $user->isSuperAdmin();

        // جلب البيانات مع الختمات دفعة واحدة لتفادي استعلامات N+1
        $family->load([
            'memberships.user',
            'khatmas' => fn($q) => $q->latest(),
            'creator',
        ]);

        // تصفية المجموعات مباشرة من الذاكرة دون استعلامات إضافية لقاعدة البيانات
        $activeMembers  = $family->memberships->where('status', 'active');
        $pendingMembers = $family->memberships->where('status', 'pending');

        $stats = [
            'active_members'    => $activeMembers->count(),
            'pending_members'   => $pendingMembers->count(),
            'active_khatmas'    => $family->khatmas->where('status', 'active')->count(),
            'completed_khatmas' => $family->khatmas->where('status', 'completed')->count(),
        ];

        return view('families.show', compact(
            'family', 'membership', 'isAdmin',
            'activeMembers', 'pendingMembers', 'stats'
        ));
    }

    public function join(Family $family)
    {
        /** @var User $user */
        $user = Auth::user();

        $exists = FamilyMember::where('user_id', $user->id)
            ->where('family_id', $family->id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'لديك طلب سابق أو أنت عضو بالفعل.');
        }

        FamilyMember::create([
            'user_id'   => $user->id,
            'family_id' => $family->id,
            'status'    => 'pending',
            'role'      => 'member',
        ]);
        NotificationService::onMemberJoined($family->id, $user);

        return back()->with('success', 'تم إرسال طلب الانضمام ✅ بانتظار موافقة المسؤول');
    } // 👈 تم إصلاح الخطأ وإغلاق قوس الدالة هنا بنجاح

    public function approveMember(Family $family, FamilyMember $member)
    {
        abort_unless($this->isAdmin($family), 403);
        abort_unless($member->family_id === $family->id, 404);

        $targetUserId = $member->user_id; // حفظ معرف العضو قبل معالجة السجل

        $member->approve(Auth::id());
        
        // إرسال الإشعار ليدخل حساب العضو الآخر فوراً
        NotificationService::onFamilyRequestAccepted($family->id, $targetUserId);

        return back()->with('success', 'تم قبول العضو في العائلة 🎉');
    }

    public function rejectMember(Family $family, FamilyMember $member)
    {
        abort_unless($this->isAdmin($family), 403);
        abort_unless($member->family_id === $family->id, 404);

        $targetUserId = $member->user_id; // حفظ معرف العضو

        $member->reject();

        if ($targetUserId) {
            try {
                // إرسال الإشعار لحساب العضو المرفوض ليراه في لوحته
                NotificationService::onFamilyRequestRejected($family->id, $targetUserId);
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send reject notification: " . $e->getMessage());
            }
        }

        return back()->with('success', 'تم رفض الطلب');
    }

    public function removeMember(Family $family, FamilyMember $member)
    {
        abort_unless($this->isAdmin($family), 403);
        abort_unless($member->family_id === $family->id, 404);

        $targetUserId = $member->user_id; // حفظ معرف العضو الموقوف

        $member->update(['status' => 'suspended']);

        if ($targetUserId) {
            try {
                // إنشاء الإشعار وإرساله مباشرة لحساب العضو الموقوف الآخر ليظهر عنده
                NotificationService::create(
                    $targetUserId,
                    'FamilyMemberRemoved',
                    '🚫 تم إيقاف عضويتك في العائلة',
                    "قام مسؤول العائلة بإيقاف عضويتك من عائلة \"{$family->name}\".",
                    '⚠️',
                    ['family_id' => $family->id]
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to send remove notification: " . $e->getMessage());
            }
        }

        return back()->with('success', 'تم إيقاف العضو وإرسال إشعار له');
    }

    /**
     * التحقق من صلاحية الإدارة للمستخدم الحالي داخل العائلة المحددة.
     */
    private function isAdmin(Family $family): bool
    {
        /** @var User $user */
        $user = Auth::user();
        if ($user->isSuperAdmin()) return true;

        return FamilyMember::where('user_id', $user->id)
            ->where('family_id', $family->id)
            ->where('role', 'admin')
            ->where('status', 'active')
            ->exists();
    }
}