<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // GET /notifications
    public function index()
    {
        $notifications = AppNotification::where('recipient_role', 'admin')
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    // GET /notifications/{notification}/read — เปิดดู + ไปยังลิงก์ที่เกี่ยวข้อง
    public function markRead(AppNotification $notification)
    {
        $notification->update(['is_read' => true]);

        return $notification->link_url ? redirect($notification->link_url) : back();
    }

    // POST /notifications/mark-all-read
    public function markAllRead()
    {
        AppNotification::unreadForAdmin()->update(['is_read' => true]);

        return back()->with('success', 'ทำเครื่องหมายอ่านแล้วทั้งหมด');
    }
}
