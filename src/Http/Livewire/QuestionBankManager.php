<?php
namespace Sosupp\Questionable\Http\Livewire;

use Livewire\Component;
use Sosupp\Questionable\Enums\QuestionType;
use Sosupp\Questionable\Models\QuestionBank;

class QuestionBankManager extends Component
{
    public $banks;
    public $selectedBank;
    public $questionTypes;
    public $showQuestionForm = false;
    public $newQuestion = [
        'question_text' => '',
        'question_type_id' => '',
        'options' => [],
    ];

    protected $listeners = ['refreshBanks' => '$refresh'];

    public function mount()
    {
        $this->banks = QuestionBank::with('questions')->get();
        $this->questionTypes = QuestionType::cases();
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
        ]);

        $question = $this->selectedBank->questions()->create([
            'question_text' => $this->newQuestion['question_text'],
            'question_type_id' => $this->newQuestion['question_type_id'],
        ]);

        // Add options if needed
        if (in_array($this->newQuestion['question_type_id'], [
            QuestionType::MULTIPLE_CHOICE->value,
            QuestionType::TRUE_FALSE->value,
        ])) {
            foreach ($this->newQuestion['options'] as $option) {
                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'] ?? false,
                ]);
            }
        }

        $this->reset('newQuestion');
        $this->showQuestionForm = false;
        $this->selectedBank->refresh();
        $this->emit('notify', 'Question added successfully!');
    }


    public function render()
    {
        return view('quiz-polls::livewire.question-bank-manager');
    }
    
}