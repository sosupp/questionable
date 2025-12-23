<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'quiz_id', 'started_at',
        'completed_at', 'score', 'total_questions', 'question_id',
        'ownable_id', 'ownable_type',
    ];

    public function ownable(): MorphTo
    {
        return $this->morphTo();
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function responses()
    {
        return $this->hasMany(UserQuizResponse::class, 'attempt_id');
    }

}
