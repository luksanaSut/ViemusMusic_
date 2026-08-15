<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class MakeupRequest extends Model
{
    protected $fillable = [
        'student_leave_id',
        'student_id',
        'enrollment_id',
        'original_class_schedule_id',
        'teacher_id',
        'room_id',
        'makeup_date',
        'start_time',
        'end_time',
        'delivery_mode',
        'admin_approval_status',
        'admin_reviewed_by',
        'admin_reviewed_at',
        'instructor_approval_status',
        'instructor_reviewed_at',
        'overall_status',
        'class_schedule_id',
        'is_overdue',
        'rejection_reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'makeup_date'          => 'date',
        'admin_reviewed_at'    => 'datetime',
        'instructor_reviewed_at' => 'datetime',
        'is_overdue'           => 'boolean',
    ];

    public function studentLeave(): BelongsTo
    {
        return $this->belongsTo(StudentLeave::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function originalClassSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class, 'original_class_schedule_id');
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }

    public function overallStatusLabel(): string
    {
        return match ($this->overall_status) {
            'pending'   => 'รออนุมัติ',
            'approved'  => 'อนุมัติแล้ว (จัดตารางแล้ว)',
            'rejected'  => 'ปฏิเสธ',
            'completed' => 'เรียนชดเชยเสร็จแล้ว',
            'cancelled' => 'ยกเลิก',
            default     => $this->overall_status,
        };
    }

    public function overallStatusBadgeClass(): string
    {
        return match ($this->overall_status) {
            'pending'   => 'text-bg-warning',
            'approved'  => 'text-bg-success',
            'rejected'  => 'text-bg-danger',
            'completed' => 'text-bg-primary',
            'cancelled' => 'text-bg-secondary',
            default     => 'text-bg-light',
        };
    }

    public function isFullyApproved(): bool
    {
        return $this->admin_approval_status === 'approved' && $this->instructor_approval_status === 'approved';
    }

    public function isRejectedByEither(): bool
    {
        return $this->admin_approval_status === 'rejected' || $this->instructor_approval_status === 'rejected';
    }
}
