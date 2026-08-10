<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseTransfer extends Model
{
    protected $fillable = [
        'transfer_no',
        'student_id',
        'old_enrollment_id',
        'old_course_id',
        'new_course_id',
        'new_teacher_id',
        'old_course_remaining_value',
        'new_course_price',
        'teacher_change_fee',
        'price_difference',
        'payment_status',
        'credit_issued',
        'status',
        'new_enrollment_id',
        'payment_id',
        'payment_method',
        'payment_reference',
        'payment_proof_path',
        'reason',
        'notes',
        'transferred_by',
        'completed_at',
    ];

    protected $casts = ['completed_at' => 'datetime'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function oldEnrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'old_enrollment_id');
    }
    public function oldCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'old_course_id');
    }
    public function newCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'new_course_id');
    }
    public function newTeacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class, 'new_teacher_id');
    }
    public function newEnrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class, 'new_enrollment_id');
    }
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_payment' => 'รอชำระเงินเพิ่ม',
            'completed'        => 'เปลี่ยนคอร์สสำเร็จ',
            'cancelled'        => 'ยกเลิก',
            default            => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending_payment' => 'text-bg-warning',
            'completed'        => 'text-bg-success',
            'cancelled'        => 'text-bg-secondary',
            default            => 'text-bg-light',
        };
    }

    public function priceDifferenceLabel(): string
    {
        if ($this->price_difference > 0) return 'ต้องชำระเพิ่ม ฿' . number_format($this->price_difference, 2);
        if ($this->price_difference < 0) return 'ได้รับเครดิตคืน ฿' . number_format(abs($this->price_difference), 2);
        return 'ไม่มีส่วนต่าง';
    }
}
