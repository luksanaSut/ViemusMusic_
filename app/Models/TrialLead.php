<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrialLead extends Model
{
    protected $fillable = [
        'lead_no', 'student_name', 'nickname', 'date_of_birth', 'age', 'guardian_name', 'phone', 'email',
        'line_id', 'course_id', 'teacher_id', 'room_id', 'interest', 'preferred_schedule', 'trial_date',
        'trial_start_time', 'trial_end_time', 'delivery_mode', 'trial_fee', 'payment_status', 'paid_at',
        'status', 'trial_result', 'teacher_feedback', 'next_follow_up_date', 'converted_student_id',
        'result_recorded_at', 'result_recorded_by',
        'converted_at', 'source', 'notes', 'created_by',
        'confirmation_status', 'guardian_confirmed_at', 'guardian_confirmed_by',
        'teacher_confirmed_at', 'teacher_confirmed_by', 'confirmation_notes',
        'checked_in_at', 'checked_in_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date', 'trial_date' => 'date', 'next_follow_up_date' => 'date',
        'paid_at' => 'datetime', 'converted_at' => 'datetime', 'trial_fee' => 'decimal:2',
        'guardian_confirmed_at' => 'datetime', 'teacher_confirmed_at' => 'datetime', 'checked_in_at' => 'datetime',
        'result_recorded_at' => 'datetime',
    ];

    public function course(): BelongsTo { return $this->belongsTo(Course::class); }
    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function convertedStudent(): BelongsTo { return $this->belongsTo(Student::class, 'converted_student_id'); }
    public function payments(): HasMany { return $this->hasMany(TrialPayment::class); }

    public function confirmedPaidAmount(): float
    {
        $payments = (float) $this->payments()->where('type', 'payment')->where('status', 'confirmed')->sum('amount');
        $refunds = (float) $this->payments()->where('type', 'refund')->where('status', 'confirmed')->sum('amount');
        return max(0, $payments - $refunds);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'new' => 'ผู้สนใจใหม่', 'contacted' => 'ติดต่อแล้ว', 'scheduled' => 'นัดทดลองแล้ว',
            'completed' => 'ทดลองแล้ว', 'converted' => 'สมัครเรียนแล้ว', 'lost' => 'ไม่ดำเนินการต่อ', default => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'new' => 'text-bg-info', 'contacted' => 'text-bg-secondary', 'scheduled' => 'text-bg-primary',
            'completed' => 'text-bg-warning', 'converted' => 'text-bg-success', 'lost' => 'text-bg-dark', default => 'text-bg-light',
        };
    }

    public function resultLabel(): string
    {
        return match ($this->trial_result) {
            'interested' => 'สนใจสมัคร', 'considering' => 'ขอพิจารณา', 'not_interested' => 'ไม่สนใจ',
            'no_show' => 'ไม่มาตามนัด', default => 'ยังไม่บันทึก',
        };
    }

    public function confirmationStatusLabel(): string
    {
        return match ($this->confirmation_status) {
            'pending' => 'รอคอนเฟิร์ม', 'guardian_confirmed' => 'ผู้ปกครองคอนเฟิร์มแล้ว',
            'teacher_confirmed' => 'ครูคอนเฟิร์มแล้ว', 'fully_confirmed' => 'ยืนยันครบแล้ว',
            'unreachable' => 'ติดต่อไม่ได้', 'reschedule_requested' => 'ขอเลื่อน',
            'cancelled' => 'ยกเลิก', 'no_show' => 'ไม่มาตามนัด', default => $this->confirmation_status,
        };
    }

    public function confirmationStatusBadgeClass(): string
    {
        return match ($this->confirmation_status) {
            'pending' => 'text-bg-secondary', 'guardian_confirmed' => 'text-bg-info',
            'teacher_confirmed' => 'text-bg-info', 'fully_confirmed' => 'text-bg-success',
            'unreachable' => 'text-bg-warning', 'reschedule_requested' => 'text-bg-warning',
            'cancelled' => 'text-bg-dark', 'no_show' => 'text-bg-danger', default => 'text-bg-light',
        };
    }

    public function markGuardianConfirmed(string $by): void
    {
        $this->guardian_confirmed_at = now();
        $this->guardian_confirmed_by = $by;
        $this->syncConfirmationStatus();
        $this->save();
    }

    public function markTeacherConfirmed(string $by): void
    {
        $this->teacher_confirmed_at = now();
        $this->teacher_confirmed_by = $by;
        $this->syncConfirmationStatus();
        $this->save();
    }

    public function setConfirmationStatus(string $status, ?string $notes, string $by): void
    {
        if ($status === 'guardian_confirmed') {
            $this->confirmation_notes = $notes;
            $this->markGuardianConfirmed($by);
            return;
        }

        if ($status === 'pending') {
            $this->guardian_confirmed_at = null;
            $this->guardian_confirmed_by = null;
            $this->teacher_confirmed_at = null;
            $this->teacher_confirmed_by = null;
        }

        $this->confirmation_status = $status;
        $this->confirmation_notes = $notes;
        $this->save();
    }

    private function syncConfirmationStatus(): void
    {
        if (!in_array($this->confirmation_status, ['pending', 'guardian_confirmed', 'teacher_confirmed'], true)) {
            return;
        }

        $this->confirmation_status = match (true) {
            $this->guardian_confirmed_at && $this->teacher_confirmed_at => 'fully_confirmed',
            (bool) $this->guardian_confirmed_at => 'guardian_confirmed',
            (bool) $this->teacher_confirmed_at => 'teacher_confirmed',
            default => 'pending',
        };
    }
}
