<!-- resources/views/livewire/poll-taker.blade.php -->
<div class="poll-container">
    @if(!$pollCompleted)
        <div class="poll-header">
            <h2>{{ $poll->title }}</h2>
            <p>{{ $poll->description }}</p>
            
            @if($poll->is_anonymous)
                <div class="poll-notice">
                    <i class="fas fa-user-secret"></i> This is an anonymous poll
                </div>
            @endif
        </div>
        
        <div class="poll-questions">
            @foreach($poll->questions as $question)
                <div class="question-card">
                    <h3>{{ $question->question_text }}</h3>
                    
                    @if(in_array($question->question_type_id, [
                        \App\Enums\QuestionType::MULTIPLE_CHOICE->value,
                        \App\Enums\QuestionType::TRUE_FALSE->value
                    ]))
                        <div class="options-list">
                            @foreach($question->options as $option)
                                <div class="option">
                                    <input 
                                        type="{{ $question->question_type_id === \App\Enums\QuestionType::MULTIPLE_CHOICE->value ? 'radio' : 'radio' }}"
                                        id="option-{{ $option->id }}"
                                        name="question-{{ $question->id }}"
                                        value="{{ $option->id }}"
                                        wire:model="userResponses.{{ $question->id }}.option_id"
                                        wire:change="saveResponse({{ $question->id }}, {{ $option->id }})"
                                    >
                                    <label for="option-{{ $option->id }}">{{ $option->option_text }}</label>
                                </div>
                            @endforeach
                        </div>
                    @elseif($question->question_type_id === \App\Enums\QuestionType::SHORT_ANSWER->value)
                        <textarea 
                            class="short-answer-input"
                            wire:model.defer="userResponses.{{ $question->id }}.answer_text"
                            wire:change="saveResponse({{ $question->id }}, null, $event.target.value)"
                            placeholder="Type your answer here..."
                        ></textarea>
                    @elseif($question->question_type_id === \App\Enums\QuestionType::RATING_SCALE->value)
                        <div class="rating-scale">
                            @for($i = 1; $i <= 5; $i++)
                                <label class="rating-option">
                                    <input 
                                        type="radio"
                                        name="question-{{ $question->id }}"
                                        value="{{ $i }}"
                                        wire:model="userResponses.{{ $question->id }}.option_id"
                                        wire:change="saveResponse({{ $question->id }}, {{ $i }})"
                                    >
                                    <span>{{ $i }}</span>
                                </label>
                            @endfor
                            <div class="scale-labels">
                                <span>Poor</span>
                                <span>Excellent</span>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <div class="poll-actions">
            <button wire:click="submitPoll" class="btn-submit">
                Submit Poll
            </button>
        </div>
    @else
        <div class="poll-complete">
            <div class="thank-you-message">
                <i class="fas fa-check-circle"></i>
                <h3>{{ $thankYouMessage }}</h3>
                <p>Your responses have been recorded.</p>
            </div>
            
            @if(!$poll->is_anonymous)
                <div class="response-summary">
                    <h4>Your Responses</h4>
                    @foreach($poll->questions as $question)
                        <div class="response-item">
                            <p><strong>{{ $question->question_text }}</strong></p>
                            <p>
                                @if($question->question_type_id === \App\Enums\QuestionType::SHORT_ANSWER->value)
                                    {{ $userResponses[$question->id]['answer_text'] }}
                                @else
                                    {{ $question->options->find($userResponses[$question->id]['option_id'])->option_text ?? 'No response' }}
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
</div>