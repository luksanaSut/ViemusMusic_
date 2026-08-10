<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentPointTransaction extends Model
{
    protected $fillable = ['student_id', 'sale_order_id', 'type', 'points', 'balance_after', 'reason'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
