<?php
namespace Sosupp\Questionable\Livewire\Polls;

use Livewire\Component;
use Sosupp\Questionable\Models\Poll;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\QuestionType;

class PollManager extends Component
{
    public $polls;
    public $selectedPoll;
    public $questionBanks = [];
    public $questionTypes = [];
    public $showPollForm = false;
    public $showQuestionForm = false;

    public $newPoll = [
        'title' => '',
        'description' => '',
        'is_anonymous' => true,
        'is_active' => true,
    ];

    public $newQuestion = [
        'question_text' => '',
        'question_bank_id' => null,
        'question_type_id' => 1,
        'options' => [],
    ];

    protected $rules = [
        'newPoll.title' => 'required|string|max:255',
        'newQuestion.question_text' => 'required|string',
    ];

    public function mount()
    {
        $this->polls = Poll::with('questions.options')->get();
        $this->questionBanks = QuestionBank::with('questions')
        ->get();

        $this->questionTypes = QuestionType::query()->get();

        // dd($this->questionBanks);
        if(empty($this->questionBanks)){
            QuestionBank::query()
            ->updateOrCreate(
                ['slug' => 'general'],
                [
                    'name' => 'general',
                ]
            );

            $this->questionBanks = QuestionBank::with('questions')->get();
        }

        // dd($this->questionBanks, empty($this->questionBanks));
    }

    public function createPoll()
    {
        $this->validate([
            'newPoll.title' => 'required',
        ]);

        // dd('zs');

        $poll = Poll::create($this->newPoll);

        $this->reset('newPoll');
        $this->showPollForm = false;
        $this->polls->push($poll);
        $this->dispatch('notify', 'Poll created successfully!');
    }

    public function addQuestion()
    {
        $this->validate([
            'newQuestion.question_text' => 'required',
        ]);

        $bank = QuestionBank::where('id', $this->newQuestion['question_bank_id'])->first();

        // dd(QuestionBank::where('id', $this->newQuestion['question_bank_id'])->first());


        // dd($this->selectedPoll, $this->newQuestion);
        $question = $this->selectedPoll->questions()->create([
            'question_bank_id' => $bank->id,
            'question_text' => $this->newQuestion['question_text'],
            'question_type_id' => $this->newQuestion['question_type_id'],
        ]);

        foreach ($this->newQuestion['options'] as $option) {
            $question->options()->create([
                'option_text' => $option['text'],
                'is_correct' => false, // For polls, is_correct doesn't apply
            ]);
        }

        $this->reset('newQuestion');
        $this->showQuestionForm = false;
        $this->selectedPoll->refresh();
        $this->dispatch('notify', 'Question added successfully!');
    }

    public function selectPoll($pollId)
    {
        $this->selectedPoll = Poll::with('questions.options')->find($pollId);
        $this->showPollForm = false;
        $this->showQuestionForm = false;
    }

    public function deletePoll($pollId)
    {
        Poll::find($pollId)->delete();
        $this->polls = $this->polls->filter(fn($poll) => $poll->id != $pollId);
        $this->dispatch('notify', 'Poll deleted successfully!');
    }

    public function deleteQuestion($questionId)
    {
        $this->selectedPoll->questions()->find($questionId)->delete();
        $this->selectedPoll->refresh();
        $this->dispatch('notify', 'Question deleted successfully!');
    }

    public function addOption()
    {
        $this->newQuestion['options'][] = ['text' => '', 'is_correct' => false];
    }

    public function removeOption($index)
    {
        unset($this->newQuestion['options'][$index]);
        $this->newQuestion['options'] = array_values($this->newQuestion['options']);
    }

    public function render()
    {
        return view('questionable::livewire.polls.poll-manager');
    }
}
