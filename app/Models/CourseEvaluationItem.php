<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseEvaluationItem extends Model
{
    protected $fillable = ['course_evaluation_id', 'evaluation_category_id', 'score', 'comment'];

    public function courseEvaluation(): BelongsTo
    {
        return $this->belongsTo(CourseEvaluation::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(EvaluationCategory::class, 'evaluation_category_id');
    }
}