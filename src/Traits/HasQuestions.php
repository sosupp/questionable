<?php
namespace Sosupp\Questionable\Traits;

use Sosupp\Questionable\Models\Question;
use Sosupp\Questionable\Models\QuestionBank;


trait HasQuestions
{
    /**
     * Get all questions belonging to this question bank
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Add a question to this question bank
     */
    public function addQuestion(array $attributes)
    {
        return $this->questions()->create($attributes);
    }

    /**
     * Import questions from another question bank
     */
    public function importQuestionsFrom(QuestionBank $sourceBank, array $questionIds = [])
    {
        $questions = $sourceBank->questions()
            ->when(!empty($questionIds), function($query) use ($questionIds) {
                $query->whereIn('id', $questionIds);
            })
            ->get();

        foreach ($questions as $question) {
            if (!$this->questions->contains($question->id)) {
                $this->questions()->attach($question->id);
            }
        }

        return $this->load('questions');
    }

    /**
     * Count questions by subject
     */
    public function countQuestionsBySubject()
    {
        return $this->questions()
            ->with('subject')
            ->selectRaw('subject_id, count(*) as count')
            ->groupBy('subject_id')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->subject->name => $item->count];
            });
    }

    /**
     * Count questions by academic level
     */
    public function countQuestionsByAcademicLevel()
    {
        return $this->questions()
            ->with('academicLevel')
            ->selectRaw('academic_level_id, count(*) as count')
            ->groupBy('academic_level_id')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->academicLevel->name => $item->count];
            });
    }

    /**
     * Get questions filtered by various criteria
     */
    public function filteredQuestions(array $filters = [])
    {
        return $this->questions()
            ->when(isset($filters['subject_id']), function($query) use ($filters) {
                $query->where('subject_id', $filters['subject_id']);
            })
            ->when(isset($filters['academic_level_id']), function($query) use ($filters) {
                $query->where('academic_level_id', $filters['academic_level_id']);
            })
            ->when(isset($filters['year_id']), function($query) use ($filters) {
                $query->where('year_id', $filters['year_id']);
            })
            ->when(isset($filters['difficulty_level']), function($query) use ($filters) {
                $query->where('difficulty_level', $filters['difficulty_level']);
            })
            ->when(isset($filters['topic']), function($query) use ($filters) {
                $query->where('topic', 'like', '%'.$filters['topic'].'%');
            })
            ->when(isset($filters['question_type_id']), function($query) use ($filters) {
                $query->where('question_type_id', $filters['question_type_id']);
            })
            ->when(isset($filters['is_active']), function($query) use ($filters) {
                $query->where('is_active', $filters['is_active']);
            })
            ->with(['subject', 'academicLevel', 'year', 'options'])
            ->get();
    }

    /**
     * Get all active questions
     */
    public function activeQuestions()
    {
        return $this->questions()->where('is_active', true)->get();
    }

    /**
     * Get all inactive questions
     */
    public function inactiveQuestions()
    {
        return $this->questions()->where('is_active', false)->get();
    }
}