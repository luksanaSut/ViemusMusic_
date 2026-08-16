<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RunThroughAttachment extends Model
{
    protected $fillable = ['run_through_id', 'file_path', 'original_name'];

    public function runThrough(): BelongsTo
    {
        return $this->belongsTo(RunThrough::class);
    }

    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }
}