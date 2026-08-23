<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPointTransaction extends Model
{
    protected $fillable = [
        'student_id',
        'sale_order_id',
        'store_sale_id',
        'type',
        'points',
        'balance_after',
        'reason',
        'expires_at',
        'remaining_points',
        'expiring_notified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'expiring_notified_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function storeSale(): BelongsTo
    {
        return $this->belongsTo(StoreSale::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'earn'       => 'สะสมแต้ม',
            'redeem'     => 'แลกแต้ม',
            'adjustment' => 'ปรับปรุงยอด',
            'expire'     => 'หมดอายุ',
            default      => $this->type,
        };
    }
}
