<?php
namespace Sosupp\Questionable\Traits;

use Sosupp\Questionable\Models\Exam;
use Sosupp\Questionable\Models\ExamAttempt;

trait Examable
{
    public function exams()
    {
        return $this->morphMany(Exam::class, 'examable');
    }

    public function createExam(array $attributes)
    {
        return $this->exams()->create($attributes);
    }

    public function hasCompletedExam(Exam $exam)
    {
        return $exam->attempts()
            ->where('user_id', auth()->id())
            ->whereNotNull('completed_at')
            ->exists();
    }

    public function examAttempts()
    {
        return $this->hasMany(ExamAttempt::class, 'user_id');
    }
}