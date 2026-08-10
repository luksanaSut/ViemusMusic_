<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentCreditTransaction extends Model
{
    protected $fillable = ['student_id', 'type', 'amount', 'balance_after', 'reason'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function typeLabel(): string
    {
        return match ($this->type) {
            'topup'      => 'เติมเครดิต',
            'use'        => 'ใช้เครดิต',
            'refund'     => 'คืนเครดิต',
            'adjustment' => 'ปรับปรุงยอด',
            default      => $this->type,
        };
    }
}
