<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherTransportFee extends Model
{
    protected $fillable = ['teacher_id', 'fee_type', 'fee_amount', 'effective_from', 'is_active'];

    protected $casts = [
        'is_active'      => 'boolean',
        'effective_from' => 'date',
    ];

    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }
}
