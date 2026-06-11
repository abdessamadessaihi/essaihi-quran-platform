<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class FamilyController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // عائلات المستخدم النشطة
        $myFamilies = Family::whereHas('memberships', fn($q) =>
            $q->where('user_id', $user->id)->where('status', 'active')
        )->withCount([
            'memberships as active_members_count' => fn($q) =>
                $q->where('status', 'active'),
            'khatmas as active_khatmas_count' => fn($q) =>
                $q->where('status', 'active'),
            'khatmas as completed_khatmas_count' => fn($q) =>
                $q->where('status', 'completed'),
        ])->get();

        // طلبات الانضمام المعلقة
        $pendingRequest = FamilyMember::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('family')
            ->first();

        // عائلات متاحة للانضمام
        $availableFamilies = Family::where('is_active', true)
            ->whereDoesntHave('memberships', fn($q) =>
                $q->where('user_id', $user->id)
                  ->whereIn('status', ['active', 'pending'])
            )->withCount([
                'memberships as active_members_count' => fn($q) =>
                    $q->where('status', 'active')
            ])->get();

        return view('families.index', compact(
            'myFamilies', 'pendingRequest', 'availableFamilies'
        ));
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

        $user = Auth::user();

        $family = Family::create([
            ...$validated,
            'created_by' => $user->id,
            'slug'       => Str::slug($validated['name']) . '-' . Str::random(5),
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

        // ترقية دور المستخدم
        if ($user->role === User::ROLE_MEMBER) {
            $user->update(['role' => User::ROLE_FAMILY_ADMIN]);
        }

        return redirect()->route('families.show', $family)
            ->with('success', 'تم إنشاء العائلة بنجاح 🎉');
    }

    public function show(Family $family)
    {
        $user = Auth::user();

        // التحقق من العضوية
        $membership = FamilyMember::where('user_id', $user->id)
            ->where('family_id', $family->id)
            ->first();

        $isAdmin = $membership?->role === 'admin' || $user->isSuperAdmin();

        $family->load([
            'memberships.user',
            'khatmas' => fn($q) => $q->latest()->take(5),
            'creator',
        ]);

        $activeMembers = $family->memberships
            ->where('status', 'active');
        $pendingMembers = $family->memberships
            ->where('status', 'pending');

        // إحصائيات العائلة
        $stats = [
            'active_members'     => $activeMembers->count(),
            'pending_members'    => $pendingMembers->count(),
            'active_khatmas'     => $family->khatmas->where('status','active')->count(),
            'completed_khatmas'  => $family->khatmas->where('status','completed')->count(),
        ];

        return view('families.show', compact(
            'family','membership','isAdmin',
            'activeMembers','pendingMembers','stats'
        ));
    }

    public function join(Family $family)
    {
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

        return back()->with('success', 'تم إرسال طلب الانضمام ✅ بانتظار موافقة المسؤول');
    }

    public function approveMember(Family $family, FamilyMember $member)
    {
        abort_unless($this->isAdmin($family), 403);
        $member->approve(Auth::id());
        return back()->with('success', 'تم قبول العضو في العائلة 🎉');
    }

    public function rejectMember(Family $family, FamilyMember $member)
    {
        abort_unless($this->isAdmin($family), 403);
        $member->reject();
        return back()->with('success', 'تم رفض الطلب');
    }

    public function removeMember(Family $family, FamilyMember $member)
    {
        abort_unless($this->isAdmin($family), 403);
        $member->update(['status' => 'suspended']);
        return back()->with('success', 'تم إيقاف العضو');
    }

    private function isAdmin(Family $family): bool
    {
        $user = Auth::user();
        if ($user->isSuperAdmin()) return true;

        return FamilyMember::where('user_id', $user->id)
            ->where('family_id', $family->id)
            ->where('role', 'admin')
            ->where('status', 'active')
            ->exists();
    }
}