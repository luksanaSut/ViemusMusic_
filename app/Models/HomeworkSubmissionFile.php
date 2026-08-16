<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeworkSubmissionFile extends Model
{
    protected $fillable = ['homework_submission_id', 'file_path', 'original_name'];

    public function submission(): BelongsTo
    {
        return $this->belongsTo(HomeworkSubmission::class, 'homework_submission_id');
    }

    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }
}