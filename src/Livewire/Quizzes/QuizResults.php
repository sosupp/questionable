<?php
namespace Sosupp\Questionable\Livewire\Quizzes;

use Livewire\Component;
use Sosupp\Questionable\Models\UserQuizAttempt;

class QuizResults extends Component
{
    public $attempt;
    public $quiz;
    public $responses;
    public $scorePercentage;

    public function mount($attemptId)
    {
        $this->attempt = UserQuizAttempt::with(['quiz', 'responses.question', 'responses.option'])
            ->findOrFail($attemptId);
            
        $this->quiz = $this->attempt->quiz;
        $this->responses = $this->attempt->responses;
        
        $this->scorePercentage = $this->attempt->total_questions > 0 
            ? round(($this->attempt->score / $this->attempt->total_questions) * 100, 2)
            : 0;
    }

    public function render()
    {
        return view('questionable::livewire.quizzes.quiz-results');
    }
}