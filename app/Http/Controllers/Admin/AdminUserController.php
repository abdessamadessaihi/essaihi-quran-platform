<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Models\XpTransaction;
use App\Models\Streak;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $role   = $request->get('role');
        $status = $request->get('status');

        $users = User::query()
            ->when($search, fn($q) =>
                $q->where('name','like',"%{$search}%")
                  ->orWhere('email','like',"%{$search}%")
            )
            ->when($role,   fn($q) => $q->where('role',   $role))
            ->when($status !== null, fn($q) => $q->where('is_active', $status))
            ->withCount([
                'dailyWards as wards_count',
                'memorizations as mem_count',
            ])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $stats = [
            'total'   => User::count(),
            'active'  => User::where('is_active',true)->count(),
            'admins'  => User::where('role','family_admin')->count(),
            'new_week'=> User::where('created_at','>=',now()->startOfWeek())->count(),
        ];

        return view('admin.users.index', compact('users','stats','search','role','status'));
    }

    public function show(User $user)
    {
        $user->load('families','badges','streak');
        $xp     = (int) XpTransaction::where('user_id',$user->id)->sum('points');
        $streak = Streak::firstOrCreate(['user_id'=>$user->id]);
        return view('admin.users.show', compact('user','xp','streak'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:120',
            'role'      => 'required|in:super_admin,family_admin,member',
            'is_active' => 'boolean',
        ]);
        $user->update($validated);
        return back()->with('success','تم تحديث بيانات المستخدم ✅');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $msg = $user->is_active ? 'تم تفعيل الحساب ✅' : 'تم إيقاف الحساب';
        return back()->with('success', $msg);
    }

    public function sendNotification(Request $request, User $user)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'type'    => 'nullable|string|max:100',
        ]);

        Notification::create([
            'user_id' => $user->id,
            'type'    => $validated['type'] ?? 'AdminMessage',
            'data'    => [
                'title'   => 'رسالة من الإدارة',
                'message' => $validated['message'],
                'from'    => auth()->user()->name,
            ],
            'channel' => 'database',
            'is_read' => false,
            'sent_at' => now(),
        ]);

        return back()->with('success','تم إرسال الإشعار بنجاح 📨');
    }

    public function broadcastNotification(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'role'    => 'nullable|in:all,family_admin,member',
        ]);

        $query = User::where('is_active', true);
        if ($validated['role'] && $validated['role'] !== 'all') {
            $query->where('role', $validated['role']);
        }

        $users = $query->get();
        $count = 0;

        foreach ($users as $u) {
            Notification::create([
                'user_id' => $u->id,
                'type'    => 'AdminBroadcast',
                'data'    => [
                    'title'   => 'إشعار من الإدارة',
                    'message' => $validated['message'],
                    'from'    => auth()->user()->name,
                ],
                'channel' => 'database',
                'is_read' => false,
                'sent_at' => now(),
            ]);
            $count++;
        }

        return back()->with('success', "تم إرسال الإشعار لـ {$count} مستخدم 📨");
    }

    public function destroy(User $user)
    {
        abort_if($user->id === auth()->id(), 403, 'لا يمكن حذف حسابك الخاص');
        $user->delete();
        return redirect()->route('admin.users.index')
                         ->with('success','تم حذف المستخدم');
    }
}