<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RunThrough extends Model
{
    protected $fillable = [
        'enrollment_id',
        'teacher_id',
        'title',
        'description',
        'practice_result',
        'areas_to_improve',
        'teacher_comment',
        'result_recorded_at',
        'created_by',
    ];

    protected $casts = ['result_recorded_at' => 'datetime'];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function attachments(): HasMany
    {
        return $this->hasMany(RunThroughAttachment::class);
    }

    public function practiceResultLabel(): string
    {
        return match ($this->practice_result) {
            'excellent'      => 'ดีเยี่ยม',
            'good'           => 'ดี',
            'needs_practice' => 'ต้องฝึกเพิ่ม',
            default          => 'ยังไม่บันทึกผล',
        };
    }

    public function practiceResultBadgeClass(): string
    {
        return match ($this->practice_result) {
            'excellent'      => 'text-bg-success',
            'good'           => 'text-bg-primary',
            'needs_practice' => 'text-bg-warning',
            default          => 'text-bg-light',
        };
    }
}