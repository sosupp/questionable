<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class QuizQuestion extends Pivot
{
    use HasFactory;

    protected $table = 'quiz_questions';
}
