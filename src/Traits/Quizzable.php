<?php
namespace Sosupp\Questionable\Traits;

use Sosupp\Questionable\Models\Quiz;

trait Quizzable
{
    public function quizzes()
    {
        return $this->morphMany(Quiz::class, 'quizzable');
    }

    public function createQuiz(array $attributes)
    {
        return $this->quizzes()->create($attributes);
    }
    
}