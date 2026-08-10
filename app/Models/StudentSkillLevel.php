<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSkillLevel extends Model
{
    protected $fillable = ['student_id', 'instrument_id', 'level_id', 'assessed_date', 'note'];

    protected $casts = ['assessed_date' => 'date'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
