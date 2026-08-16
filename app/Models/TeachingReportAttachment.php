<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingReportAttachment extends Model
{
    protected $fillable = ['teaching_report_id', 'file_path', 'original_name'];

    public function teachingReport(): BelongsTo
    {
        return $this->belongsTo(TeachingReport::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }
}