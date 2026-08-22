<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = ['product_id', 'type', 'quantity', 'balance_after', 'reason', 'store_sale_id', 'created_by'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function storeSale(): BelongsTo
    {
        return $this->belongsTo(StoreSale::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'in'         => 'รับเข้า',
            'out'        => 'ตัดออก (ขาย)',
            'adjustment' => 'ปรับปรุงสต็อก',
            default      => $this->type,
        };
    }

    public function typeBadgeClass(): string
    {
        return match ($this->type) {
            'in'         => 'text-bg-success',
            'out'        => 'text-bg-danger',
            'adjustment' => 'text-bg-warning',
            default      => 'text-bg-light',
        };
    }
}