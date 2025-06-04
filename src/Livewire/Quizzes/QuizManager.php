<?php
namespace Sosupp\Questionable\Livewire\Quizzes;

use Livewire\Component;
use Illuminate\Support\Str;
use Sosupp\Questionable\Models\Quiz;
use Sosupp\Questionable\Models\Year;
use Sosupp\Questionable\Models\Subject;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\AcademicLevel;

class QuizManager extends Component
{
    public $quizzes;
    public $selectedQuiz;
    public $questionBanks;
    public $subjects;
    public $academicLevels;
    public $years;
    public $showQuizForm = false;
    
    public $newQuiz = [
        'title' => '',
        'description' => '',
        'time_limit' => 30,
        'is_active' => true,
        'shuffle_questions' => false,
        'shuffle_options' => false,
        'show_correct_answers' => false,
        'passing_score' => null,
    ];

    protected $rules = [
        'newQuiz.title' => 'required|string|max:255',
        'newQuiz.time_limit' => 'required|integer|min:1',
    ];

    public function mount()
    {
        $this->quizzes = Quiz::with('questions')->get();
        $this->questionBanks = QuestionBank::with('questions')->get();
        $this->subjects = Subject::orderBy('name')->get();
        $this->academicLevels = AcademicLevel::orderBy('order')->get();
        $this->years = Year::orderBy('start_year', 'desc')->get();
    }

    public function createQuiz()
    {
        $this->validate([
            'newQuiz.title' => 'required',
            'newQuiz.time_limit' => 'required|integer|min:1',
        ]);

        $quiz = Quiz::create($this->newQuiz);
        
        $this->reset('newQuiz');
        $this->showQuizForm = false;
        $this->quizzes->push($quiz);
        $this->emit('notify', 'Quiz created successfully!');
    }

    public function selectQuiz($quizId)
    {
        $this->selectedQuiz = Quiz::with('questions')->find($quizId);
        $this->showQuizForm = false;
    }
    
    public function deleteQuiz($quizId)
    {
        Quiz::find($quizId)->delete();
        $this->quizzes = $this->quizzes->filter(fn($quiz) => $quiz->id != $quizId);
        $this->emit('notify', 'Quiz deleted successfully!');
    }
    
    public function attachQuestions(array $questionIds)
    {
        $this->selectedQuiz->questions()->attach($questionIds);
        $this->selectedQuiz->refresh();
        $this->emit('notify', 'Questions added successfully!');
    }
    
    public function detachQuestion($questionId)
    {
        $this->selectedQuiz->questions()->detach($questionId);
        $this->selectedQuiz->refresh();
        $this->emit('notify', 'Question removed successfully!');
    }

    public function render()
    {
        return view('questionable::livewire.quizzes.quiz-manager');
    }
}