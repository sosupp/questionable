<?php
namespace Sosupp\Questionable\Livewire\Polls;


use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Sosupp\Questionable\Models\Poll;

class PollTaker extends Component
{
    public $poll;
    public $userResponses = [];
    public $pollCompleted = false;
    public $thankYouMessage = 'Thank you for participating in this poll!';

    public function mount($pollId)
    {
        $this->poll = Poll::with('questions.options')->findOrFail($pollId);
        
        // Initialize responses array
        foreach ($this->poll->questions as $question) {
            $this->userResponses[$question->id] = [
                'option_id' => null,
                'answer_text' => null,
            ];
        }
    }

    public function saveResponse($questionId, $optionId = null, $answerText = null)
    {
        $this->userResponses[$questionId] = [
            'option_id' => $optionId,
            'answer_text' => $answerText,
        ];
    }

    public function submitPoll()
    {
        if ($this->pollCompleted) {
            return;
        }

        foreach ($this->userResponses as $questionId => $response) {
            PollResponse::create([
                'poll_id' => $this->poll->id,
                'question_id' => $questionId,
                'user_id' => $this->poll->is_anonymous ? null : Auth::id(),
                'option_id' => $response['option_id'],
                'answer_text' => $response['answer_text'],
            ]);
        }
        
        $this->pollCompleted = true;
    }

    public function render()
    {
        return view('questionable::livewire.polls.poll-taker');
    }
}