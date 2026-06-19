<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminFamilyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        $families = Family::query()
            ->when($search, fn($q) =>
                $q->where('name','like',"%{$search}%")
            )
            ->withCount([
                'memberships as active_count' => fn($q) =>
                    $q->where('status','active'),
                'khatmas as khatmas_count',
                'khatmas as active_khatmas' => fn($q) =>
                    $q->where('status','active'),
            ])
            ->with('creator')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total'    => Family::count(),
            'active'   => Family::where('is_active',true)->count(),
            'total_members' => FamilyMember::where('status','active')->count(),
        ];

        return view('admin.families.index', compact('families','stats','search'));
    }

    public function show(Family $family)
    {
        $family->load([
            'memberships.user',
            'khatmas',
            'creator',
        ]);
        return view('admin.families.show', compact('family'));
    }

    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);
        $family->update($validated);
        return back()->with('success', 'تم تحديث حالة العائلة ✅');
    }

    public function sendNotification(Request $request, Family $family)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $members = $family->activeMembers()->get();
        $count   = 0;

        foreach ($members as $member) {
            Notification::create([
                'user_id' => $member->id,
                'type'    => 'FamilyAdminMessage',
                'data'    => [
                    'title'   => "رسالة للعائلة: {$family->name}",
                    'message' => $validated['message'],
                    'from'    => auth()->user()->name,
                ],
                'channel' => 'database',
                'is_read' => false,
                'sent_at' => now(),
            ]);
            $count++;
        }

        return back()->with('success', "تم إرسال الإشعار لـ {$count} عضو 📨");
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return redirect()->route('admin.families.index')
                         ->with('success','تم حذف العائلة');
    }
}