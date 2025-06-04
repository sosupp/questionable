<?php
namespace Sosupp\Questionable\Livewire;

use Livewire\Component;
use Sosupp\Questionable\Models\Year;
use Sosupp\Questionable\Models\Subject;
use Sosupp\Questionable\Enums\QuestionType;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\AcademicLevel;
use Sosupp\Questionable\Services\QuestionBankService;

class QuestionBankManager extends Component
{
    public $banks;
    public $selectedBank;
    public $questionTypes;
    public $subjects;
    public $academicLevels;
    public $years;
    public $showQuestionForm = false;
    public $filters = [
        'subject_id' => null,
        'academic_level_id' => null,
        'year_id' => null,
        'difficulty_level' => null,
        'topic' => null,
    ];

    public $newQuestion = [
        'question_text' => '',
        'question_type_id' => '',
        'subject_id' => null,
        'academic_level_id' => null,
        'year_id' => null,
        'difficulty_level' => null,
        'topic' => null,
        'options' => [],
        'meta' => [
            'blooms_taxonomy_level' => null,
            'learning_objective' => null,
        ],
    ];

    protected $listeners = ['refreshBanks' => '$refresh'];

    public function mount()
    {
        $this->banks = QuestionBank::with('questions')->get();
        $this->questionTypes = QuestionType::cases();
        $this->subjects = Subject::orderBy('name')->get();
        $this->academicLevels = AcademicLevel::orderBy('order')->get();
        $this->years = Year::orderBy('start_year', 'desc')->get();
        
        // Set current year as default
        $currentYear = Year::current();
        if ($currentYear) {
            $this->newQuestion['year_id'] = $currentYear->id;
        }
    }

    public function selectBank($bankId)
    {
        $this->selectedBank = QuestionBank::with('questions.options')->find($bankId);
        $this->showQuestionForm = false;
    }

    public function addQuestion()
    {
        $this->validate([
            'newQuestion.question_text' => 'required',
            'newQuestion.question_type_id' => 'required|exists:question_types,id',
            'newQuestion.subject_id' => 'nullable|exists:subjects,id',
            'newQuestion.academic_level_id' => 'nullable|exists:academic_levels,id',
            'newQuestion.year_id' => 'nullable|exists:years,id',
            'newQuestion.difficulty_level' => 'nullable|integer|between:1,5',
        ]);

        $question = app(QuestionBankService::class)
            ->addQuestionToBank($this->selectedBank, $this->newQuestion);

        $this->reset('newQuestion');
        $this->showQuestionForm = false;
        $this->selectedBank->refresh();
        $this->emit('notify', 'Question added successfully!');
    }   

    public function applyFilters()
    {
        $this->selectedBank->load([
            'questions' => function($query) {
                if ($this->filters['subject_id']) {
                    $query->where('subject_id', $this->filters['subject_id']);
                }
                if ($this->filters['academic_level_id']) {
                    $query->where('academic_level_id', $this->filters['academic_level_id']);
                }
                if ($this->filters['year_id']) {
                    $query->where('year_id', $this->filters['year_id']);
                }
                if ($this->filters['difficulty_level']) {
                    $query->where('difficulty_level', $this->filters['difficulty_level']);
                }
                if ($this->filters['topic']) {
                    $query->where('topic', 'like', '%'.$this->filters['topic'].'%');
                }
            },
            'questions.subject',
            'questions.academicLevel',
            'questions.year'
        ]);
    }

    public function resetFilters()
    {
        $this->reset('filters');
        $this->selectedBank->load([
            'questions',
            'questions.subject',
            'questions.academicLevel',
            'questions.year'
        ]);
    }

    public function render()
    {
        return view('questionable::livewire.question-bank-manager');
    }
    
}