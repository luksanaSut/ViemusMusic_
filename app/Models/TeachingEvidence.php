<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeachingEvidence extends Model
{
    protected $table = 'teaching_evidences';

    protected $fillable = [
        'teaching_log_id',
        'file_type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'uploaded_by_user_id',
        'uploaded_by_name',
    ];

    public function teachingLog(): BelongsTo
    {
        return $this->belongsTo(TeachingLog::class);
    }
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by_user_id');
    }

    public function url(): string
    {
        return asset('storage/' . $this->file_path);
    }

    public function fileTypeLabel(): string
    {
        return match ($this->file_type) {
            'image'    => 'รูปภาพ',
            'video'    => 'วิดีโอ',
            'document' => 'เอกสาร',
            default    => $this->file_type,
        };
    }

    public function fileTypeIcon(): string
    {
        return match ($this->file_type) {
            'image'    => 'bi-image',
            'video'    => 'bi-camera-video',
            'document' => 'bi-file-earmark-text',
            default    => 'bi-file-earmark',
        };
    }

    public function formattedSize(): string
    {
        if (!$this->file_size) return '-';
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 1) . ' ' . $units[$i];
    }
}