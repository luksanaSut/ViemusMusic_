<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamResult extends Model
{
    protected $fillable = [
        'student_id',
        'instrument_id',
        'exam_board',
        'grade',
        'exam_date',
        'result',
        'score',
        'certificate_no',
        'note',
    ];

    protected $casts = ['exam_date' => 'date'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
    public function instrument(): BelongsTo
    {
        return $this->belongsTo(Instrument::class);
    }

    public function examBoardLabel(): string
    {
        return match ($this->exam_board) {
            'abrsm'   => 'ABRSM',
            'trinity' => 'Trinity College London',
            default   => $this->exam_board,
        };
    }

    public function resultLabel(): string
    {
        return match ($this->result) {
            'distinction' => 'Distinction',
            'merit'       => 'Merit',
            'pass'        => 'Pass',
            'fail'        => 'Fail',
            default       => '-',
        };
    }
}
