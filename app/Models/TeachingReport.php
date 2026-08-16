<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeachingReport extends Model
{
    protected $fillable = ['teaching_log_id', 'content_taught', 'homework', 'progress_notes', 'notes', 'created_by'];

    public function teachingLog(): BelongsTo
    {
        return $this->belongsTo(TeachingLog::class);
    }
    public function attachments(): HasMany
    {
        return $this->hasMany(TeachingReportAttachment::class);
    }
    public function homeworkSubmissions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(HomeworkSubmission::class)->orderByDesc('version');
    }

    public function latestHomeworkSubmission(): ?HomeworkSubmission
    {
        return $this->homeworkSubmissions->first();
    }
}