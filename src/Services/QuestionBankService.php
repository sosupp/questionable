<?php
namespace Sosupp\Questionable\Services;

use Sosupp\Questionable\Models\Question;
use Sosupp\Questionable\Models\QuestionBank;

class QuestionBankService
{
    public function createQuestionBank(array $data, $owner)
    {
        return QuestionBank::create([
            'name' => $data['name'],
            'slug' => str($data['name'])->slug()->value(),
            'description' => $data['description'] ?? null,
            'owner_id' => $owner->id,
            'owner_type' => get_class($owner),
        ]);
    }

    public function addQuestionToBank(QuestionBank $bank, array $questionData)
    {
        $question = $bank->questions()->create([
            'question_type_id' => $questionData['question_type_id'],
            'question_text' => $questionData['question_text'],
            'metadata' => $questionData['metadata'] ?? null,
            'points' => $questionData['points'] ?? 1,
            'subject_id' => $questionData['subject_id'] ?? null,
            'academic_level_id' => $questionData['academic_level_id'] ?? null,
            'year_id' => $questionData['year_id'] ?? Year::current()?->id,
            'difficulty_level' => $questionData['difficulty_level'] ?? null,
            'topic' => $questionData['topic'] ?? null,
        ]);
        
        // Add options if needed
        if (isset($questionData['options'])) {
            foreach ($questionData['options'] as $option) {
                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'] ?? false,
                ]);
            }
        }

        // Add metadata if provided
        if (isset($questionData['meta'])) {
            foreach ($questionData['meta'] as $key => $value) {
                $question->meta()->create([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }

        return $question;
    }

    public function importQuestions(QuestionBank $bank, array $questionIds)
    {
        $questions = Question::whereIn('id', $questionIds)->get();
        
        foreach ($questions as $question) {
            if (!$bank->questions->contains($question->id)) {
                $bank->questions()->attach($question->id);
            }
        }
        
        return $bank->fresh();
    }

    public function filterQuestions(QuestionBank $bank, array $filters)
    {
        $query = $bank->questions()->with(['subject', 'academicLevel', 'year']);
        
        if (isset($filters['subject_id'])) {
            $query->where('subject_id', $filters['subject_id']);
        }
        
        if (isset($filters['academic_level_id'])) {
            $query->where('academic_level_id', $filters['academic_level_id']);
        }
        
        if (isset($filters['year_id'])) {
            $query->where('year_id', $filters['year_id']);
        }
        
        if (isset($filters['difficulty_level'])) {
            $query->where('difficulty_level', $filters['difficulty_level']);
        }
        
        if (isset($filters['topic'])) {
            $query->where('topic', 'like', '%'.$filters['topic'].'%');
        }
        
        return $query->get();
    }
    
}