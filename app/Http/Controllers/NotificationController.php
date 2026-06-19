<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $notifications = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        $unread = $notifications->where('is_read', false);
        $read   = $notifications->where('is_read', true);
        $unreadCount = $unread->count();

        return view('notifications.index', compact(
            'notifications', 'unread', 'read', 'unreadCount'
        ));
    }

    public function markRead(Notification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'تم تعيين الإشعار كمقروء');
    }

    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        return back()->with('success', 'تم تعيين جميع الإشعارات كمقروءة ✅');
    }

    public function destroy(Notification $notification)
    {
        abort_unless($notification->user_id === Auth::id(), 403);
        $notification->delete();
        return back();
    }
}