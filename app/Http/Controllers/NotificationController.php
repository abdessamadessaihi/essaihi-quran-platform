<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // ─── عرض قائمة الإشعارات ──────────────────────────────
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();

        // تجميع حسب الحالة
        $unread = $notifications->where('is_read', false);
        $read   = $notifications->where('is_read', true);

        return view('notifications.index', compact(
            'notifications',
            'unreadCount',
            'unread',
            'read'
        ));
    }

    // ─── تعيين إشعار واحد كمقروء ──────────────────────────
    public function markRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return back()->with('success', 'تم تعيين الإشعار كمقروء');
    }

    // ─── تعيين كل الإشعارات كمقروءة ──────────────────────
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return back()->with('success', 'تم تعيين جميع الإشعارات كمقروءة');
    }

    // ─── حذف إشعار ────────────────────────────────────────
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return back()->with('success', 'تم حذف الإشعار');
    }
}