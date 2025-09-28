<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Sosupp\SlimDashboard\Concerns\Filters\CommonScopes;

class Quiz extends Model
{
    use HasFactory, CommonScopes;

    protected $fillable = [
        'quizzable_id', 'quizzable_type',
        'title', 'slug', 'description', 'time_limit', 'is_active',
        'year_id', 'code', 'version', 'shuffle_questions', 'shuffle_options', 
        'show_correct_answers', 'starts_at', 'ends_at'
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')
        ->using(QuizQuestion::class)
        ->withPivot(['order'])
        ->withTimestamps();
    }

    public function quizzable() 
    {
        return $this->morphTo();
    }

    public function attempts()
    {
        return $this->hasMany(UserQuizAttempt::class);
    }
}
