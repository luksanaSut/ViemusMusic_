<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportCompensation extends Model
{
    protected $table = 'transport_compensations';

    protected $fillable = ['teacher_id', 'compensation_date', 'amount', 'reason', 'created_by'];

    protected $casts = ['compensation_date' => 'date'];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
}