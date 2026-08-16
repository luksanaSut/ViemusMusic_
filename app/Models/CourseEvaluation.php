<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseEvaluation extends Model
{
    protected $fillable = ['enrollment_id', 'overall_comment', 'status', 'evaluated_by', 'evaluated_at'];

    protected $casts = ['evaluated_at' => 'datetime'];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(CourseEvaluationItem::class);
    }

    public function averageScore(): float
    {
        return round($this->items->avg('score') ?? 0, 1);
    }

    public function statusLabel(): string
    {
        return $this->status === 'published' ? 'เผยแพร่แล้ว' : 'ฉบับร่าง';
    }
}