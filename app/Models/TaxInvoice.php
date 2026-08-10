<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaxInvoice extends Model
{
    protected $fillable = [
        'sale_order_id',
        'invoice_no',
        'invoice_type',
        'is_company',
        'buyer_name',
        'buyer_tax_id',
        'buyer_address',
        'buyer_phone',
        'subtotal',
        'vat_rate',
        'vat_amount',
        'total_amount',
        'issued_date',
    ];

    protected $casts = [
        'is_company'  => 'boolean',
        'issued_date' => 'date',
    ];

    public function saleOrder(): BelongsTo
    {
        return $this->belongsTo(SaleOrder::class);
    }

    public function invoiceTypeLabel(): string
    {
        return $this->invoice_type === 'tax_invoice' ? 'ใบกำกับภาษีเต็มรูป' : 'ใบเสร็จรับเงิน';
    }
}
