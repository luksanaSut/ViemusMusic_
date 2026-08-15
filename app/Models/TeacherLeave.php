<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherLeave extends Model
{
    protected $fillable = [
        'teacher_id',
        'leave_date_from',
        'leave_date_to',
        'reason',
        'status',
        'created_by',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'leave_date_from' => 'date',
        'leave_date_to'   => 'date',
        'reviewed_at'     => 'datetime',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
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

    // คาบเรียนที่ได้รับผลกระทบในช่วงที่ขอลา
    public function affectedSchedules()
    {
        return ClassSchedule::where('teacher_id', $this->teacher_id)
            ->whereBetween('schedule_date', [$this->leave_date_from->toDateString(), $this->leave_date_to->toDateString()])
            ->whereIn('status', ['scheduled', 'completed'])
            ->with('enrollment.student')
            ->get();
    }
}
