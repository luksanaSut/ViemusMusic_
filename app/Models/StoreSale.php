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
        'promotion_id',
        'promotion_code',
        'auto_promotion_id',
        'total_amount',
        'discount_amount',
        'auto_discount_amount',
        'net_payable',
        'payment_method',
        'payment_proof_path',
        'payment_reference',
        'confirmed_at',
        'status',
        'sold_by',
        'ordered_by_user_id',
        'delivery_method',
        'delivery_recipient_name',
        'delivery_phone',
        'delivery_address',
        'delivery_tracking_no',
        'delivery_status',
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
    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
    public function autoPromotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class, 'auto_promotion_id');
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

    public function deliveryMethodLabel(): string
    {
        return $this->delivery_method === 'delivery' ? 'จัดส่งถึงบ้าน' : 'รับสินค้าที่ร้าน (โรงเรียน)';
    }

    public function deliveryStatusLabel(): string
    {
        return match ($this->delivery_status) {
            'preparing'        => 'กำลังเตรียมสินค้า',
            'shipped'          => 'จัดส่งแล้ว',
            'ready_for_pickup' => 'พร้อมให้รับที่ร้าน',
            'picked_up'        => 'รับสินค้าแล้ว',
            'delivered'        => 'จัดส่งถึงแล้ว',
            default            => '-',
        };
    }

    public function deliveryStatusBadgeClass(): string
    {
        return match ($this->delivery_status) {
            'preparing'        => 'text-bg-warning',
            'shipped', 'ready_for_pickup' => 'text-bg-primary',
            'picked_up', 'delivered'      => 'text-bg-success',
            default            => 'text-bg-light',
        };
    }
}
