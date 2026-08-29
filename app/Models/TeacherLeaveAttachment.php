<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherLeaveAttachment extends Model
{
    protected $fillable = ['teacher_leave_id', 'file_path', 'original_name', 'mime_type', 'file_size'];

    public function teacherLeave(): BelongsTo
    {
        return $this->belongsTo(TeacherLeave::class);
    }

    public function formattedSize(): string
    {
        return $this->file_size >= 1048576
            ? number_format($this->file_size / 1048576, 1) . ' MB'
            : number_format(max(1, $this->file_size / 1024), 0) . ' KB';
    }
}
