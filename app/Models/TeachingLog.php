<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingLog extends Model
{
    protected $fillable = [
        'class_schedule_id',
        'enrollment_id',
        'teacher_id',
        'student_id',
        'attendance_status',
        'checked_in_at',
        'checked_in_by',
        'confirmed_duration_minutes',
        'is_extra_time',
        'confirmed_at',
        'confirmed_by',
        'student_leave_id',
        'teaching_session_id',
        'session_deducted',
        'notes',
    ];

    protected $casts = [
        'checked_in_at'  => 'datetime',
        'confirmed_at'   => 'datetime',
        'is_extra_time'  => 'boolean',
        'session_deducted' => 'boolean',
    ];

    public function classSchedule(): BelongsTo
    {
        return $this->belongsTo(ClassSchedule::class);
    }
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function studentLeave(): BelongsTo
    {
        return $this->belongsTo(StudentLeave::class);
    }
    public function teachingSession(): BelongsTo
    {
        return $this->belongsTo(TeachingSession::class);
    }

    public function attendanceStatusLabel(): string
    {
        return match ($this->attendance_status) {
            'present'       => 'เข้าเรียน',
            'late'          => 'เข้าเรียนสาย',
            'absent'        => 'ขาดเรียน',
            'excused_leave' => 'ลา (ไม่นับตัดคอร์ส)',
            default         => 'ยังไม่เช็คชื่อ',
        };
    }

    public function attendanceStatusBadgeClass(): string
    {
        return match ($this->attendance_status) {
            'present'       => 'text-bg-success',
            'late'          => 'text-bg-warning',
            'absent'        => 'text-bg-danger',
            'excused_leave' => 'text-bg-secondary',
            default         => 'text-bg-light',
        };
    }

    public function durationLabel(): string
    {
        if (!$this->confirmed_duration_minutes) return 'ยังไม่ยืนยัน';
        $label = $this->confirmed_duration_minutes . ' นาที';
        return $this->is_extra_time ? $label . ' (สอนเพิ่ม)' : $label;
    }
}