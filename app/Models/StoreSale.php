<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StoreSale extends Model
{
    protected $fillable = [
        'sale_no',
        'buyer_name',
        'student_id',
        'total_amount',
        'payment_method',
        'payment_proof_path',
        'payment_reference',
        'confirmed_at',
        'status',
        'sold_by',
        'ordered_by_user_id',
    ];

    protected $casts = ['confirmed_at' => 'datetime'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function orderedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by_user_id');
    }
    public function items(): HasMany
    {
        return $this->hasMany(StoreSaleItem::class);
    }

    public function paymentMethodLabel(): string
    {
        return match ($this->payment_method) {
            'cash'        => 'เงินสด',
            'transfer'    => 'โอนเงิน',
            'credit_card' => 'บัตรเครดิต',
            'promptpay'   => 'PromptPay/QR',
            default       => $this->payment_method,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending_payment' => 'รอชำระเงิน',
            'completed'        => 'สำเร็จ',
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
}
