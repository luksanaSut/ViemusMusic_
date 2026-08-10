<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentLeave extends Model
{
    protected $fillable = [
        'student_id',
        'enrollment_id',
        'leave_type',
        'leave_date',
        'reason',
        'is_makeup_required',
        'makeup_date',
        'makeup_status',
    ];

    protected $casts = [
        'leave_date'          => 'date',
        'makeup_date'         => 'date',
        'is_makeup_required'  => 'boolean',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function leaveTypeLabel(): string
    {
        return $this->leave_type === 'emergency' ? 'ลาฉุกเฉิน' : 'ลาปกติ';
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
