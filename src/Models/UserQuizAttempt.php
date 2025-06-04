<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuizAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'quiz_id', 'started_at', 
        'completed_at', 'score', 'total_questions'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function responses()
    {
        return $this->hasMany(UserQuizResponse::class);
    }

}
