<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RescheduleRequest extends Model
{
    protected $fillable = [
        'type',
        'class_schedule_id',
        'swap_with_class_schedule_id',
        'new_teacher_id',
        'new_room_id',
        'new_date',
        'new_start_time',
        'new_end_time',
        'snapshot_before',
        'status',
        'reason',
        'rejection_reason',
        'requested_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'new_date'        => 'date',
        'reviewed_at'     => 'datetime',
        'snapshot_before' => 'array',
    ];

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }
    public function swapWithClassSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'swap_with_class_schedule_id');
    }
    public function newTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'new_teacher_id');
    }
    public function newRoom(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'new_room_id');
    }

    public function typeLabel(): string
    {
        return $this->type === 'swap' ? 'แลกคาบเรียน' : 'เปลี่ยนแปลงตารางเรียน';
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'approved' => 'อนุมัติแล้ว',
            'rejected' => 'ปฏิเสธ',
            default => 'รออนุมัติ',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'approved' => 'text-bg-success',
            'rejected' => 'text-bg-secondary',
            default => 'text-bg-warning',
        };
    }
}
