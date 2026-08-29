<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /notifications
    public function index(Request $request)
    {
        $filter = $request->get('filter') === 'unread' ? 'unread' : 'all';
        $base = AppNotification::forUser($request->user());
        $unreadCount = (clone $base)->where('is_read', false)->count();
        $notifications = $base
            ->when($filter === 'unread', fn ($query) => $query->where('is_read', false))
            ->orderByDesc('created_at')
            ->paginate(20)->withQueryString();

        return view('notifications.index', compact('notifications', 'filter', 'unreadCount'));
    }

    // GET /notifications/{notification}/read — เปิดดู + ไปยังลิงก์ที่เกี่ยวข้อง
    public function markRead(Request $request, AppNotification $notification)
    {
        // กันไม่ให้เปิดอ่าน/เข้าถึงแจ้งเตือนของคนอื่น (เช่น อาจารย์คนหนึ่งเปิดของอีกคนโดยเดา id)
        abort_unless(
            AppNotification::forUser($request->user())->whereKey($notification->id)->exists(),
            403
        );

        $notification->update(['is_read' => true]);

        return $notification->link_url ? redirect($notification->link_url) : back();
    }

    // POST /notifications/mark-all-read
    public function markAllRead(Request $request)
    {
        AppNotification::unreadForUser($request->user())->update(['is_read' => true]);

        return back()->with('success', 'ทำเครื่องหมายอ่านแล้วทั้งหมด');
    }
}
