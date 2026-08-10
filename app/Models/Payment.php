<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'student_id',
        'enrollment_id',
        'invoice_no',
        'amount',
        'paid_amount',
        'due_date',
        'paid_date',
        'method',
        'status',
        'note',
    ];

    protected $casts = [
        'due_date'  => 'date',
        'paid_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'paid'    => 'ชำระแล้ว',
            'partial' => 'ชำระบางส่วน',
            'pending' => 'รอชำระ',
            'overdue' => 'ค้างชำระ',
            default   => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'paid'    => 'text-bg-success',
            'partial' => 'text-bg-warning',
            'pending' => 'text-bg-light',
            'overdue' => 'text-bg-danger',
            default   => 'text-bg-light',
        };
    }

    public function outstandingAmount(): float
    {
        return (float) $this->amount - (float) $this->paid_amount;
    }
}
