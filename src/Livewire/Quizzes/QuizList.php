<?php
namespace Sosupp\Questionable\Livewire\Quizzes;

use Livewire\Component;
use Sosupp\Questionable\Models\Quiz;

class QuizList extends Component
{
    public $quizzes;

    public function mount()
    {
        $this->quizzes = Quiz::where('is_active', true)->get();
    }

    public function render()
    {
        return view('questionable::livewire.quizzes.quiz-list');
    }
}