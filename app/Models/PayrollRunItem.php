<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollRunItem extends Model
{
    protected $fillable = ['payroll_run_id', 'teaching_session_id', 'income_amount'];

    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }
    public function teachingSession(): BelongsTo
    {
        return $this->belongsTo(TeachingSession::class);
    }
}