<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreSaleItem extends Model
{
    protected $fillable = ['store_sale_id', 'product_id', 'product_name', 'quantity', 'unit_price', 'subtotal'];

    public function storeSale(): BelongsTo
    {
        return $this->belongsTo(StoreSale::class);
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
