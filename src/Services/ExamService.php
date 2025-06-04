<?php
namespace Sosupp\Questionable\Services;

use Sosupp\Questionable\Models\Exam;
use Sosupp\Questionable\Models\Question;
use Sosupp\Questionable\Models\ExamSection;

class ExamService
{
    public function createExam(array $data, $examable)
    {
        $exam = $examable->exams()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'total_time' => $data['total_time'] ?? null,
            'passing_score' => $data['passing_score'] ?? null,
            'max_attempts' => $data['max_attempts'] ?? 1,
            'shuffle_sections' => $data['shuffle_sections'] ?? false,
            'shuffle_questions' => $data['shuffle_questions'] ?? false,
            'shuffle_options' => $data['shuffle_options'] ?? false,
            'require_proctoring' => $data['require_proctoring'] ?? false,
            'show_score_after' => $data['show_score_after'] ?? false,
            'show_answers_after' => $data['show_answers_after'] ?? false,
            'available_from' => $data['available_from'] ?? null,
            'available_to' => $data['available_to'] ?? null,
        ]);

        if (isset($data['sections'])) {
            $this->addSections($exam, $data['sections']);
        }

        return $exam;
    }

    public function addSections(Exam $exam, array $sections)
    {
        $order = 1;
        
        foreach ($sections as $sectionData) {
            $section = $exam->sections()->create([
                'title' => $sectionData['title'],
                'description' => $sectionData['description'] ?? null,
                'time_limit' => $sectionData['time_limit'] ?? null,
                'order' => $order++,
            ]);
            
            if (isset($sectionData['questions'])) {
                $this->attachQuestionsToSection($section, $sectionData['questions']);
            }
        }
        
        return $exam;
    }

    public function attachQuestionsToSection(ExamSection $section, array $questionIds)
    {
        $questions = Question::whereIn('id', $questionIds)->get();
        
        $order = 1;
        foreach ($questions as $question) {
            $section->questions()->attach($question->id, ['order' => $order++]);
        }
        
        return $section;
    }

    public function startExamAttempt(Exam $exam, $user)
    {
        // Check if user has remaining attempts
        $attemptCount = $exam->attempts()->where('user_id', $user->id)->count();
        
        if ($exam->max_attempts > 0 && $attemptCount >= $exam->max_attempts) {
            throw new \Exception('Maximum attempts reached for this exam');
        }
        
        // Check if exam is available
        if (!$exam->isAvailable()) {
            throw new \Exception('Exam is not currently available');
        }
        
        return $exam->attempts()->create([
            'user_id' => $user->id,
            'started_at' => now(),
        ]);
    }
    
}