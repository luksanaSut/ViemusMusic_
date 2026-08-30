<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'course_id',
        'teacher_id',
        'enrolled_date',
        'expected_end_date',
        'actual_end_date',
        'sessions_used',
        'extension_months_used',
        'status',
    ];

    protected $casts = [
        'enrolled_date'      => 'date',
        'expected_end_date'  => 'date',
        'actual_end_date'    => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }


    public function leaves(): HasMany
    {
        return $this->hasMany(StudentLeave::class);
    }

    // ใบสั่งซื้อที่ทำให้เกิดการลงทะเบียนนี้ (เก็บวัน/เวลาที่สะดวกตอนสมัคร)
    public function saleOrder(): HasOne
    {
        return $this->hasOne(SaleOrder::class)->latestOfMany();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'active'    => 'กำลังเรียน',
            'completed' => 'เรียนจบแล้ว',
            'cancelled' => 'ยกเลิก',
            'paused'    => 'พักเรียน',
            default     => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'active'    => 'text-bg-success',
            'paused'    => 'text-bg-warning',
            'completed' => 'text-bg-secondary',
            'cancelled' => 'text-bg-secondary',
            default     => 'text-bg-light',
        };
    }

    // จำนวนครั้งเรียนคงเหลือ (เฉพาะคอร์สแบบปกติที่นับจำนวนครั้ง)
    public function remainingSessions(): ?int
    {
        if (!$this->course || !$this->course->total_sessions) {
            return null;
        }
        return max(0, $this->course->total_sessions - $this->sessions_used);
    }

    // ตัดจำนวนครั้งเรียนไป 1 ครั้ง (Business rule: ตัดเมื่อเข้าเรียนจริงเท่านั้น ไม่ใช่ตอนลา)
    public function deductSession(): void
    {
        $this->increment('sessions_used');
    }

    // คืนจำนวนครั้งเรียน (เผื่อกรณีแก้ไขสถานะย้อนหลัง)
    public function restoreSession(): void
    {
        if ($this->sessions_used > 0) {
            $this->decrement('sessions_used');
        }
    }

    // มูลค่าคงเหลือของคอร์สนี้ ใช้สำหรับคำนวณตอนเปลี่ยนคอร์ส (FR-CS-004)
    // ใช้ได้กับคอร์สแบบนับจำนวนครั้งเท่านั้น — คอร์สแบบไม่จำกัดจำนวนครั้ง (Private ต่อเนื่อง) ถือว่ามูลค่าคงเหลือ = 0
    public function remainingValue(): float
    {
        if (!$this->course || !$this->course->total_sessions || !$this->course->price) {
            return 0;
        }

        $ratio = $this->remainingSessions() / $this->course->total_sessions;
        return round((float) $this->course->price * $ratio, 2);
    }

    // Business rule: สิทธิ์ขยายเวลาคงเหลือ อ้างอิงจาก Course::maxExtensionMonths()
    public function remainingExtensionMonths(): int
    {
        if (!$this->course) return 0;
        return max(0, $this->course->maxExtensionMonths() - $this->extension_months_used);
    }



    public function canExtend(): bool
    {
        return $this->remainingExtensionMonths() > 0;
    }

    // จำนวนครั้งที่ใช้สิทธิ์ลาฉุกเฉินไปแล้ว เทียบกับ course.emergency_leave_quota
    public function emergencyLeaveUsed(): int
    {
        return $this->leaves()->where('leave_type', 'emergency')->count();
    }

    public function emergencyLeaveQuota(): int
    {
        return $this->course->emergency_leave_quota ?? 0;
    }

    public function emergencyLeaveRemaining(): int
    {
        return max(0, $this->emergencyLeaveQuota() - $this->emergencyLeaveUsed());
    }
}
