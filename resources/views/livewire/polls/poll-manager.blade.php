<!-- resources/views/livewire/poll-manager.blade.php -->
<div class="poll-manager-container">
    <div class="header">
        <h2>Poll Manager</h2>
        <button wire:click="$toggle('showPollForm')" class="btn-primary">
            {{ $showPollForm ? 'Cancel' : '+ New Poll' }}
        </button>
    </div>

    @if($showPollForm)
        <div class="poll-form">
            <h3>Create New Poll</h3>

            <div class="form-group">
                <label>Poll Title</label>
                <input type="text" wire:model="newPoll.title" required>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea wire:model="newPoll.description"></textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" wire:model="newPoll.is_anonymous">
                    Anonymous Responses
                </label>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" wire:model="newPoll.is_active">
                    Active
                </label>
            </div>

            <div class="form-actions">
                <button wire:click="createPoll" class="btn-submit">Create Poll</button>
            </div>
        </div>
    @endif

    @if($selectedPoll && !$showPollForm)
        <div class="poll-details">
            <div class="poll-header">
                <h3>{{ $selectedPoll->title }}</h3>
                <p>{{ $selectedPoll->description }}</p>

                <div class="poll-meta">
                    <span>Status: {{ $selectedPoll->is_active ? 'Active' : 'Inactive' }}</span>
                    <span>Responses: {{ $selectedPoll->responses->count() }}</span>
                    <span>Type: {{ $selectedPoll->is_anonymous ? 'Anonymous' : 'Identified' }}</span>
                </div>

                <div class="poll-actions">
                    <button wire:click="$toggle('showQuestionForm')" class="btn-primary">
                        {{ $showQuestionForm ? 'Cancel' : '+ Add Question' }}
                    </button>
                    <button wire:click="deletePoll({{ $selectedPoll->id }})" class="btn-danger">
                        Delete Poll
                    </button>
                </div>
            </div>

            @if($showQuestionForm)
                <div class="question-form">
                    <h4>Add New Question</h4>

                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea wire:model="newQuestion.question_text" required></textarea>
                    </div>

                    <div class="form-group">
                        <label>Question bank</label>
                        <select wire:model="newQuestion.question_bank_id">
                            <option value="0">select</option>
                            @forelse ($this->questionBanks as $bank)
                            <option value="{{$bank->id}}">{{$bank->name}}</option>
                            @empty

                            @endforelse
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Question Type</label>
                        <select wire:model="newQuestion.question_type_id">
                            @forelse ($this->questionTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @empty

                            @endforelse
                        </select>
                    </div>

                    @if(in_array($this->newQuestion['question_type_id'], [
                        $this->questionTypes->pluck('id')
                    ]))
                        <div class="form-group">
                            <label>Options</label>
                            <div class="options-list">
                                @foreach($newQuestion['options'] as $index => $option)
                                    <div class="option-item">
                                        <input type="text"
                                            wire:model="newQuestion.options.{{ $index }}.text"
                                            placeholder="Option text">
                                        <button type="button"
                                            wire:click="removeOption({{ $index }})">×</button>
                                    </div>
                                @endforeach
                                <button type="button"
                                    wire:click="addOption"
                                    class="btn-add-option">
                                    + Add Option
                                </button>
                            </div>
                        </div>
                    @endif

                    <div class="form-actions">
                        <button wire:click="addQuestion" class="btn-submit">Add Question</button>
                    </div>
                </div>
            @endif

            <div class="questions-list">
                <h4>Poll Questions</h4>

                @if($selectedPoll->questions->isEmpty())
                    <p>No questions added yet.</p>
                @else
                    @foreach($selectedPoll->questions as $question)
                        <div class="question-card">
                            <div class="question-header">
                                <h5>{{ $question->question_text }}</h5>
                                <span class="question-type">
                                    {{ $question->questionType?->name}}
                                </span>
                                <button wire:click="deleteQuestion({{ $question->id }})" class="btn-danger">
                                    Delete
                                </button>
                            </div>

                            @if($question->options->isNotEmpty())
                                <div class="options-list">
                                    @foreach($question->options as $option)
                                        <div class="option-item">
                                            {{ $option->option_text }}
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @elseif(!$showPollForm)
        <div class="polls-list">
            @if($polls->isEmpty())
                <p>No polls created yet.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Responses</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($polls as $poll)
                            <tr wire:click="selectPoll({{ $poll->id }})" class="{{ $selectedPoll && $selectedPoll->id === $poll->id ? 'active' : '' }}">
                                <td>{{ $poll->title }}</td>
                                <td>{{ $poll->questions->count() }}</td>
                                <td>{{ $poll->responses->count() }}</td>
                                <td>{{ $poll->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button wire:click.stop="deletePoll({{ $poll->id }})" class="btn-danger">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
</div>
