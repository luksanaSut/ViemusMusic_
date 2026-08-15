<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class StudentLeave extends Model
{
    protected $fillable = [
        'student_id',
        'enrollment_id',
        'class_schedule_id',
        'leave_type',
        'leave_date',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'is_makeup_required',
        'makeup_date',
        'makeup_status',
    ];

    protected $casts = [
        'leave_date'         => 'date',
        'makeup_date'        => 'date',
        'reviewed_at'        => 'datetime',
        'is_makeup_required' => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function makeupRequest(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(MakeupRequest::class);
    }

    public function leaveTypeLabel(): string
    {
        return match ($this->leave_type) {
            'emergency'  => 'ลาฉุกเฉิน',
            'no_makeup'  => 'ลาแบบไม่ชดเชย',
            default      => 'ลาปกติ',
        };
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

    public function makeupStatusLabel(): string
    {
        return match ($this->makeup_status) {
            'pending'      => 'รอจัดตาราง',
            'scheduled'    => 'นัดชดเชยแล้ว',
            'completed'    => 'เรียนชดเชยแล้ว',
            'not_required' => 'ไม่ต้องชดเชย',
            default        => $this->makeup_status,
        };
    }
}
