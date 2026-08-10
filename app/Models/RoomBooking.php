<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomBooking extends Model
{
    protected $fillable = [
        'room_id',
        'teacher_id',
        'course_id',
        'booking_date',
        'start_time',
        'end_time',
        'purpose',
        'attendees_count',
        'status',
        'booked_by',
    ];

    protected $casts = ['booking_date' => 'date'];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function statusLabel(): string
    {
        return $this->status === 'confirmed' ? 'ยืนยันแล้ว' : 'ยกเลิก';
    }
}
