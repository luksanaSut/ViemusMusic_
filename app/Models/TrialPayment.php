<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrialPayment extends Model
{
    protected $fillable = [
        'transaction_no', 'trial_lead_id', 'parent_payment_id', 'type', 'amount', 'payment_method',
        'status', 'transaction_at', 'reference_no', 'proof_path', 'proof_original_name', 'notes',
        'created_by', 'confirmed_by', 'confirmed_at',
    ];

    protected $casts = ['amount' => 'decimal:2', 'transaction_at' => 'datetime', 'confirmed_at' => 'datetime'];

    public function trialLead(): BelongsTo { return $this->belongsTo(TrialLead::class); }
    public function parentPayment(): BelongsTo { return $this->belongsTo(self::class, 'parent_payment_id'); }
    public function refunds(): HasMany { return $this->hasMany(self::class, 'parent_payment_id'); }

    public function methodLabel(): string
    {
        return match ($this->payment_method) {
            'cash' => 'เงินสด', 'transfer' => 'โอนธนาคาร', 'promptpay' => 'PromptPay/QR',
            'credit_card' => 'บัตรเครดิต', 'other' => 'อื่น ๆ', default => $this->payment_method,
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) { 'pending' => 'รอตรวจสอบ', 'confirmed' => 'ยืนยันแล้ว', 'cancelled' => 'ยกเลิก', default => $this->status };
    }
}
