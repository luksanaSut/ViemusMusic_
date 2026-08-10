<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAvailability extends Model
{
    protected $fillable = ['teacher_id', 'day_of_week', 'start_time', 'end_time', 'is_available'];

    protected $casts = ['is_available' => 'boolean'];

    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }

    public static function dayLabels(): array
    {
        return [0 => 'อาทิตย์', 1 => 'จันทร์', 2 => 'อังคาร', 3 => 'พุธ', 4 => 'พฤหัสบดี', 5 => 'ศุกร์', 6 => 'เสาร์'];
    }

    public function dayLabel(): string
    {
        return self::dayLabels()[$this->day_of_week] ?? '';
    }
}
