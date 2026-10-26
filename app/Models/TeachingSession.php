<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class TeachingSession extends Model
{
    protected $fillable = [
        'teacher_id', 'instrument_id', 'teaching_type_id', 'level_id',
        'student_name', 'session_date', 'start_time', 'end_time', 'hours',
        'rate_applied', 'transport_fee_applied', 'income_amount', 'status', 'note',
    ];

    protected $casts = [
        'session_date' => 'date',
    ];

    public function teacher(): BelongsTo { return $this->belongsTo(Teacher::class); }
    public function instrument(): BelongsTo { return $this->belongsTo(Instrument::class); }
    public function teachingType(): BelongsTo { return $this->belongsTo(TeachingType::class); }
    public function level(): BelongsTo { return $this->belongsTo(Level::class); }

    // คำนวณจำนวนชั่วโมงจาก start_time / end_time อัตโนมัติก่อนบันทึก
    protected static function booted()
    {
        static::saving(function (TeachingSession $session) {
            if ($session->start_time && $session->end_time) {
                $start = Carbon::parse($session->start_time);
                $end   = Carbon::parse($session->end_time);
                $session->hours = round($start->diffInMinutes($end) / 60, 2);
            }

            // คำนวณรายได้ = ชั่วโมง * เรท (หรือ fixed ต่อคาบ)
            // ไม่รวมค่ารถ เพราะค่ารถแยกเก็บใน transport_fee_applied และระบบเงินเดือนบวกแยกให้อยู่แล้ว
            if ($session->rate_applied !== null) {
                $session->income_amount = round($session->hours * $session->rate_applied, 2);
            }
        });
    }
}
