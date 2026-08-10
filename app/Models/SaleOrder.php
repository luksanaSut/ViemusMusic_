<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SaleOrder extends Model
{
    protected $fillable = [
        'order_no',
        'student_id',
        'course_id',
        'teacher_id',
        'coupon_id',
        'coupon_code',
        'branch',
        'delivery_mode',
        'preferred_day_of_week',
        'preferred_start_time',
        'preferred_end_time',
        'base_price',
        'discount_amount',
        'credit_used',
        'points_used',
        'points_discount_amount',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'net_payable',
        'payment_proof_path',
        'payment_method',
        'payment_reference',
        'status',
        'enrollment_id',
        'payment_id',
        'notes',
        'sold_by',
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
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }
    public function taxInvoice(): HasOne
    {
        return $this->hasOne(TaxInvoice::class);
    }

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;
        return $query->where('order_no', 'like', "%{$term}%")
            ->orWhereHas('student', fn($q) => $q->where('full_name', 'like', "%{$term}%")->orWhere('student_code', 'like', "%{$term}%"));
    }

    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_payment' => 'รอตรวจสอบการชำระเงิน',
            'paid'             => 'ชำระเงินแล้ว',
            'cancelled'        => 'ยกเลิก',
            default            => $this->status,
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending_payment' => 'text-bg-warning',
            'paid'             => 'text-bg-success',
            'cancelled'        => 'text-bg-secondary',
            default            => 'text-bg-light',
        };
    }

    public function deliveryModeLabel(): string
    {
        return match ($this->delivery_mode) {
            'onsite' => 'ที่โรงเรียน',
            'online' => 'ออนไลน์',
            'hybrid' => 'ไฮบริด',
            default => '-',
        };
    }

    public function preferredDayLabel(): ?string
    {
        if ($this->preferred_day_of_week === null) return null;
        return \App\Models\TeacherAvailability::dayLabels()[$this->preferred_day_of_week] ?? null;
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'promptpay'   => 'PromptPay / QR',
            'transfer'    => 'โอนเงินผ่านธนาคาร',
            'credit_card' => 'บัตรเครดิต/เดบิต',
            'cash'        => 'เงินสด',
            default       => 'ยังไม่เลือก',
        };
    }
}
