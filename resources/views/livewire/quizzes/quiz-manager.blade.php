<!-- resources/views/livewire/quiz-manager.blade.php -->
<div class="quiz-manager-container">
    <div class="header">
        <h2>Quiz Manager</h2>
        <button wire:click="$toggle('showQuizForm')" class="btn-primary">
            {{ $showQuizForm ? 'Cancel' : '+ New Quiz' }}
        </button>
    </div>

    @if($showQuizForm)
        <div class="quiz-form">
            <h3>Create New Quiz</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Quiz Title</label>
                    <input type="text" wire:model="newQuiz.title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea wire:model="newQuiz.description"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Time Limit (minutes)</label>
                    <input type="number" wire:model="newQuiz.time_limit" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Passing Score (%)</label>
                    <input type="number" wire:model="newQuiz.passing_score" min="0" max="100">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newQuiz.is_active">
                        Active
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newQuiz.shuffle_questions">
                        Shuffle Questions
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newQuiz.shuffle_options">
                        Shuffle Options
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newQuiz.show_correct_answers">
                        Show Correct Answers
                    </label>
                </div>
            </div>
            
            <div class="form-actions">
                <button wire:click="createQuiz" class="btn-submit">Create Quiz</button>
            </div>
        </div>
    @endif

    @if($selectedQuiz && !$showQuizForm)
        <div class="quiz-details">
            <div class="quiz-header">
                <h3>{{ $selectedQuiz->title }}</h3>
                <p>{{ $selectedQuiz->description }}</p>
                
                <div class="quiz-meta">
                    <span>Time: {{ $selectedQuiz->time_limit }} mins</span>
                    <span>Questions: {{ $selectedQuiz->questions->count() }}</span>
                    @if($selectedQuiz->passing_score)
                        <span>Passing: {{ $selectedQuiz->passing_score }}%</span>
                    @endif
                    <span>Status: {{ $selectedQuiz->is_active ? 'Active' : 'Inactive' }}</span>
                </div>
                
                <div class="quiz-actions">
                    <button wire:click="deleteQuiz({{ $selectedQuiz->id }})" class="btn-danger">
                        Delete Quiz
                    </button>
                </div>
            </div>
            
            <div class="questions-selection">
                <h4>Add Questions from Banks</h4>
                
                <div class="filter-controls">
                    <div class="filter-group">
                        <label>Subject</label>
                        <select wire:model="filters.subject_id">
                            <option value="">All Subjects</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Academic Level</label>
                        <select wire:model="filters.academic_level_id">
                            <option value="">All Levels</option>
                            @foreach($academicLevels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label>Difficulty</label>
                        <select wire:model="filters.difficulty_level">
                            <option value="">Any Difficulty</option>
                            <option value="1">Very Easy</option>
                            <option value="2">Easy</option>
                            <option value="3">Medium</option>
                            <option value="4">Hard</option>
                            <option value="5">Very Hard</option>
                        </select>
                    </div>
                </div>
                
                <div class="banks-list">
                    @foreach($questionBanks as $bank)
                        <div class="bank-card">
                            <h5>{{ $bank->name }}</h5>
                            
                            <div class="bank-questions">
                                @foreach($bank->questions as $question)
                                    @if(!$selectedQuiz->questions->contains($question->id))
                                        <div class="question-item">
                                            <label>
                                                <input type="checkbox" 
                                                    wire:model="selectedQuestions"
                                                    value="{{ $question->id }}">
                                                {{ Str::limit($question->question_text, 70) }}
                                            </label>
                                            <span class="question-meta">
                                                {{ $question->subject->name }} | 
                                                {{ $question->academicLevel->name }} | 
                                                Difficulty: {{ $question->difficulty_level }}/5
                                            </span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                
                <div class="selection-actions">
                    <button wire:click="attachQuestions(selectedQuestions)" 
                            class="btn-primary"
                            wire:loading.attr="disabled">
                        Add Selected Questions
                    </button>
                    <span wire:loading>Processing...</span>
                </div>
            </div>
            
            <div class="current-questions">
                <h4>Current Quiz Questions ({{ $selectedQuiz->questions->count() }})</h4>
                
                @if($selectedQuiz->questions->isEmpty())
                    <p>No questions added yet.</p>
                @else
                    @foreach($selectedQuiz->questions as $question)
                        <div class="question-card">
                            <div class="question-content">
                                <p>{{ $question->question_text }}</p>
                                <span class="question-meta">
                                    {{ $question->subject->name }} | 
                                    {{ $question->academicLevel->name }} | 
                                    Difficulty: {{ $question->difficulty_level }}/5
                                </span>
                            </div>
                            <button wire:click="detachQuestion({{ $question->id }})" 
                                    class="btn-danger btn-sm">
                                Remove
                            </button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @elseif(!$showQuizForm)
        <div class="quizzes-list">
            @if($quizzes->isEmpty())
                <p>No quizzes created yet.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Questions</th>
                            <th>Time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quizzes as $quiz)
                            <tr wire:click="selectQuiz({{ $quiz->id }})" 
                                class="{{ $selectedQuiz && $selectedQuiz->id === $quiz->id ? 'active' : '' }}">
                                <td>{{ $quiz->title }}</td>
                                <td>{{ $quiz->questions->count() }}</td>
                                <td>{{ $quiz->time_limit }} mins</td>
                                <td>{{ $quiz->is_active ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <button wire:click.stop="deleteQuiz({{ $quiz->id }})" 
                                            class="btn-danger">
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