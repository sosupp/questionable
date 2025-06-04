<?php
namespace Sosupp\Questionable\Traits;

use Sosupp\Questionable\Models\Question;
use Sosupp\Questionable\Models\ExamSection;

trait HasSections
{
    public function sections()
    {
        return $this->hasMany(ExamSection::class, 'exam_id');
    }

    public function addSection(array $attributes)
    {
        return $this->sections()->create($attributes);
    }

    public function questions()
    {
        return $this->hasManyThrough(
            Question::class,
            ExamSection::class,
            'exam_id',
            'id',
            'id',
            'id'
        )->whereHas('sections', function($query) {
            $query->where('exam_id', $this->id);
        });
    }

    public function totalQuestions()
    {
        return $this->sections()->withCount('questions')->get()->sum('questions_count');
    }
}