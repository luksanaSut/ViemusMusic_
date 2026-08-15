<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model
{
    protected $fillable = ['recipient_role', 'recipient_id', 'title', 'message', 'link_url', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public static function notifyAdmins(string $title, string $message, ?string $url = null): void
    {
        static::create([
            'recipient_role' => 'admin',
            'recipient_id' => null,
            'title' => $title,
            'message' => $message,
            'link_url' => $url,
        ]);
    }

    public static function notifyTeacher(int $teacherId, string $title, string $message, ?string $url = null): void
    {
        static::create([
            'recipient_role' => 'teacher',
            'recipient_id' => $teacherId,
            'title' => $title,
            'message' => $message,
            'link_url' => $url,
        ]);
    }

    public function scopeUnreadForAdmin($query)
    {
        return $query->where('recipient_role', 'admin')->where('is_read', false);
    }
}
