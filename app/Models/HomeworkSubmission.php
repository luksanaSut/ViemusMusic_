<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HomeworkSubmission extends Model
{
    protected $fillable = [
        'teaching_report_id',
        'student_id',
        'version',
        'student_note',
        'status',
        'feedback',
        'reviewed_by',
        'reviewed_at',
        'submitted_by',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function teachingReport(): BelongsTo
    {
        return $this->belongsTo(TeachingReport::class);
    }
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function files(): HasMany
    {
        return $this->hasMany(HomeworkSubmissionFile::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'approved'        => 'ผ่านแล้ว',
            'needs_revision'  => 'ต้องแก้ไข',
            default           => 'รอตรวจ',
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'approved'        => 'text-bg-success',
            'needs_revision'  => 'text-bg-danger',
            default           => 'text-bg-warning',
        };
    }
}