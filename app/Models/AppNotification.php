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

    // ดึงเฉพาะแจ้งเตือนของผู้ใช้คนนั้นจริงๆ ตาม role + ตัวตนที่ผูกไว้
    // admin เห็นแจ้งเตือนที่ส่งถึง role admin ทั้งหมด (ไม่ผูก recipient_id เฉพาะคน)
    // teacher/student/guardian เห็นเฉพาะที่ส่งตรงถึง id ของตัวเองเท่านั้น
    public function scopeForUser($query, \App\Models\User $user)
    {
        return $query->where('recipient_role', $user->role)
            ->where(function ($q) use ($user) {
                $q->whereNull('recipient_id');
                if ($user->role === 'teacher' && $user->teacher_id) {
                    $q->orWhere('recipient_id', $user->teacher_id);
                } elseif ($user->role === 'student' && $user->student_id) {
                    $q->orWhere('recipient_id', $user->student_id);
                } elseif ($user->role === 'guardian' && $user->guardian_id) {
                    $q->orWhere('recipient_id', $user->guardian_id);
                }
            });
    }

    public function scopeUnreadForUser($query, \App\Models\User $user)
    {
        return $query->forUser($user)->where('is_read', false);
    }
}
