<?php
namespace Sosupp\Questionable\Livewire\Quizzes;

use Livewire\Component;
use Sosupp\Questionable\Models\Quiz;
use Sosupp\Questionable\Models\Option;
use Sosupp\Questionable\Models\UserQuizAttempt;
use Sosupp\Questionable\Models\UserQuizResponse;

class QuizTaker extends Component
{
    public $quiz;
    public $questions;
    public $currentQuestionIndex = 0;
    public $userResponses = [];
    public $timeRemaining;
    public $attemptId;
    public $quizStarted = false;
    public $quizCompleted = false;

    protected $listeners = ['timerTick' => 'decrementTime'];

    public function mount($quizId)
    {
        $this->quiz = Quiz::with('questions.options')->findOrFail($quizId);
        $this->questions = $this->quiz->questions;
        
        // Initialize responses array
        foreach ($this->questions as $question) {
            $this->userResponses[$question->id] = [
                'option_id' => null,
                'answer_text' => null,
            ];
        }
    }

    public function startQuiz()
    {
        $this->quizStarted = true;
        
        // Create a new attempt record
        $attempt = UserQuizAttempt::create([
            'user_id' => auth()->id(),
            'quiz_id' => $this->quiz->id,
            'started_at' => now(),
            'total_questions' => $this->questions->count(),
        ]);
        
        $this->attemptId = $attempt->id;
        
        // Set time remaining if quiz has time limit
        if ($this->quiz->time_limit) {
            $this->timeRemaining = $this->quiz->time_limit * 60; // Convert to seconds
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function saveResponse($questionId, $optionId = null, $answerText = null)
    {
        $this->userResponses[$questionId] = [
            'option_id' => $optionId,
            'answer_text' => $answerText,
        ];
    }

    public function decrementTime()
    {
        if ($this->timeRemaining > 0) {
            $this->timeRemaining--;
            
            if ($this->timeRemaining === 0) {
                $this->submitQuiz();
            }
        }
    }

    public function submitQuiz()
    {
        if ($this->quizCompleted) {
            return;
        }

        $totalScore = 0;
        
        foreach ($this->userResponses as $questionId => $response) {
            $question = $this->questions->find($questionId);
            $isCorrect = false;
            $pointsEarned = 0;
            
            if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false') {
                $selectedOption = Option::find($response['option_id']);
                $isCorrect = $selectedOption ? $selectedOption->is_correct : false;
                $pointsEarned = $isCorrect ? $question->points : 0;
            } elseif ($question->question_type === 'short_answer') {
                // For short answer, we'd need some logic to check the answer
                // This is a simple example - you might want to implement more complex checking
                $correctOptions = $question->correctOptions;
                $isCorrect = $correctOptions->contains('option_text', strtolower(trim($response['answer_text'])));
                $pointsEarned = $isCorrect ? $question->points : 0;
            }
            
            UserQuizResponse::create([
                'attempt_id' => $this->attemptId,
                'question_id' => $questionId,
                'option_id' => $response['option_id'],
                'answer_text' => $response['answer_text'],
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ]);
            
            $totalScore += $pointsEarned;
        }
        
        // Update the attempt record
        $attempt = UserQuizAttempt::find($this->attemptId);
        $attempt->update([
            'completed_at' => now(),
            'score' => $totalScore,
        ]);
        
        $this->quizCompleted = true;
        $this->emit('quizCompleted', $this->attemptId);
    }

    public function render()
    {
        return view('questionable::livewire.quizzes.quiz-taker');
    }
}