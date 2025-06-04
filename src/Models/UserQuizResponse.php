<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id', 
        'question_id', 
        'option_id', 
        'answer_text', 
        'is_correct', 
        'points_earned'
    ];

    public function attempt()
    {
        return $this->belongsTo(UserQuizAttempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
