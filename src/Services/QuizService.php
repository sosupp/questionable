<?php
namespace Sosupp\Questionable\Services;

use Sosupp\Questionable\Models\Quiz;
use Sosupp\Questionable\Models\Question;

class QuizService
{
    public function createQuiz(array $data, $quizzable)
    {
        $quiz = $quizzable->quizzes()->create([
            'title' => $data['title'],
            'slug' => str($data['title'])->slug()->value(),
            'description' => $data['description'] ?? null,
            'time_limit' => $data['time_limit'] ?? null,
            'shuffle_questions' => $data['shuffle_questions'] ?? false,
            'shuffle_options' => $data['shuffle_options'] ?? false,
            'show_correct_answers' => $data['show_correct_answers'] ?? false,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        if (isset($data['questions'])) {
            $this->attachQuestions($quiz, $data['questions']);
        }

        return $quiz;
    }

    public function attachQuestions(Quiz $quiz, array $questionIds)
    {
        $questions = Question::whereIn('id', $questionIds)->get();
        
        $order = 1;
        foreach ($questions as $question) {
            $quiz->questions()->attach($question->id, ['order' => $order++]);
        }
        
        return $quiz;
    }
    
}