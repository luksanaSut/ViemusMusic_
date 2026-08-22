<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollRun extends Model
{
    protected $fillable = [
        'teacher_id',
        'period_start',
        'period_end',
        'teaching_income_total',
        'transport_fee_total',
        'adjustment_amount',
        'adjustment_reason',
        'total_amount',
        'status',
        'paid_date',
        'payment_method',
        'generated_by',
        'adjusted_by',
        'paid_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'paid_date'    => 'date',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(PayrollRunItem::class);
    }

    public function recalculateTotal(): void
    {
        $this->total_amount = round(
            $this->teaching_income_total + $this->transport_fee_total + $this->adjustment_amount,
            2
        );
        $this->save();
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'confirmed' => 'ยืนยันยอดแล้ว',
            'paid'      => 'จ่ายแล้ว',
            default     => 'ฉบับร่าง',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'confirmed' => 'text-bg-warning',
            'paid'      => 'text-bg-success',
            default     => 'text-bg-light',
        };
    }

    public function periodLabel(): string
    {
        return $this->period_start->format('d/m/Y') . ' - ' . $this->period_end->format('d/m/Y');
    }
}