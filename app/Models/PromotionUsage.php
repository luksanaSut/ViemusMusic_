<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromotionUsage extends Model
{
    protected $fillable = [
        'promotion_id',
        'sale_order_id',
        'store_sale_id',
        'student_id',
        'buyer_identifier',
        'discount_amount',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function storeSale(): BelongsTo
    {
        return $this->belongsTo(StoreSale::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
