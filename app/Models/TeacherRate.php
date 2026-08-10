<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherRate extends Model
{
    protected $fillable = [
        'teacher_id', 'teaching_type_id', 'instrument_id',
        'rate_type', 'rate_amount', 'effective_from', 'effective_to',
        'is_active', 'note',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'effective_from' => 'date',
        'effective_to'   => 'date',
    ];

    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }
    public function teachingType(): BelongsTo { return $this->belongsTo(TeachingType::class); }
    public function instrument(): BelongsTo { return $this->belongsTo(Instrument::class); }

    public function rateTypeLabel(): string
    {
        return match ($this->rate_type) {
            'per_hour'      => 'ต่อชั่วโมง',
            'per_session'   => 'ต่อคาบ/ครั้ง',
            'monthly_fixed' => 'เหมาต่อเดือน',
            default         => $this->rate_type,
        };
    }
}
