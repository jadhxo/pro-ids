<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonCompletion extends Model
{
    protected $table = 'lesson_user_completions';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
    ];
}