<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSchedule extends Model
{
    protected $fillable = [
        'enrollment_id',
        'teacher_id',
        'room_id',
        'schedule_date',
        'start_time',
        'end_time',
        'delivery_mode',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = ['schedule_date' => 'date'];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    // ===== Scopes สำหรับดูตารางแยกมุมมอง =====
    public function scopeForStudent($query, ?int $studentId)
    {
        return $studentId
            ? $query->whereHas('enrollment', fn($q) => $q->where('student_id', $studentId))
            : $query;
    }

    public function scopeForTeacher($query, ?int $teacherId)
    {
        return $teacherId ? $query->where('teacher_id', $teacherId) : $query;
    }

    public function scopeForRoom($query, ?int $roomId)
    {
        return $roomId ? $query->where('room_id', $roomId) : $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where(function ($q) use ($term) {
            $q->whereHas('enrollment.student', fn($qq) => $qq->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"))
                ->orWhereHas('enrollment.course', fn($qq) => $qq->where('name', 'like', "%{$term}%"))
                ->orWhereHas('teacher', fn($qq) => $qq->where('full_name', 'like', "%{$term}%"));
        });
    }

    // ===== ตรวจสอบตารางเรียนซ้ำ (Validation rule หลักของโมดูล) =====
    // ห้ามนักเรียน / อาจารย์ / ห้องเรียน มีเวลาซ้ำกัน ในวันเดียวกัน
    public static function findConflicts(
        string $date,
        string $startTime,
        string $endTime,
        ?int $studentId,
        ?int $teacherId,
        ?int $roomId,
        ?int $excludeId = null
    ): array {
        $overlapWhere = fn($q) => $q->where('start_time', '<', $endTime)->where('end_time', '>', $startTime);

        $base = fn() => static::where('schedule_date', $date)
            ->whereIn('status', ['scheduled', 'completed'])
            ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
            ->where($overlapWhere);

        $conflicts = [];

        if ($studentId) {
            $studentConflict = $base()->whereHas('enrollment', fn($q) => $q->where('student_id', $studentId))->first();
            if ($studentConflict) $conflicts[] = 'นักเรียนคนนี้มีตารางเรียนคาบอื่นทับซ้อนเวลาเดียวกันแล้ว';
        }

        if ($teacherId) {
            $teacherConflict = $base()->where('teacher_id', $teacherId)->first();
            if ($teacherConflict) $conflicts[] = 'อาจารย์ท่านนี้มีตารางสอนคาบอื่นทับซ้อนเวลาเดียวกันแล้ว';
        }

        if ($roomId) {
            $roomConflict = $base()->where('room_id', $roomId)->first();
            if ($roomConflict) $conflicts[] = 'ห้องเรียนนี้ถูกจองไว้ทับซ้อนเวลาเดียวกันแล้ว';
        }

        return $conflicts;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'scheduled' => 'นัดสอน',
            'completed' => 'สอนแล้ว',
            'cancelled' => 'ยกเลิก',
            'no_show' => 'ขาดเรียน',
            default => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'scheduled' => 'text-bg-warning',
            'completed' => 'text-bg-success',
            'cancelled' => 'text-bg-secondary',
            'no_show' => 'text-bg-danger',
            default => 'text-bg-light',
        };
    }

    public function deliveryModeLabel(): string
    {
        return match ($this->delivery_mode) {
            'onsite' => 'ที่โรงเรียน',
            'online' => 'ออนไลน์',
            'hybrid' => 'ไฮบริด',
            default => '-',
        };
    }
}
