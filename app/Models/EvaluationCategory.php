<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationCategory extends Model
{
    protected $fillable = ['name', 'description', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];
}