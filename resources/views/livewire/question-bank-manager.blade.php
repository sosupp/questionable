<!-- resources/views/livewire/question-bank-manager.blade.php -->
<div class="question-bank-container">
    <div class="bank-selection">
        <h2>Question Banks</h2>
        <ul class="bank-list">
            @foreach($banks as $bank)
                <li wire:click="selectBank({{ $bank->id }})" 
                    class="{{ $selectedBank && $selectedBank->id === $bank->id ? 'active' : '' }}">
                    {{ $bank->name }}
                    <span class="question-count">{{ $bank->questions->count() }}</span>
                </li>
            @endforeach
        </ul>
        <button wire:click="$emit('showCreateBank')" class="btn-add">
            + New Bank
        </button>
    </div>

    @if($selectedBank)
        <div class="bank-details">
            <div class="bank-header">
                <h3>{{ $selectedBank->name }}</h3>
                <p>{{ $selectedBank->description }}</p>
                
                <div class="bank-actions">
                    <button wire:click="showQuestionForm = true" class="btn-primary">
                        + Add Question
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <h4>Filter Questions</h4>
                <div class="filter-grid">
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
                        <label>Year</label>
                        <select wire:model="filters.year_id">
                            <option value="">All Years</option>
                            @foreach($years as $year)
                                <option value="{{ $year->id }}">{{ $year->name }}</option>
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
                    
                    <div class="filter-group">
                        <label>Topic</label>
                        <input type="text" wire:model="filters.topic" placeholder="Enter topic...">
                    </div>
                    
                    <div class="filter-actions">
                        <button wire:click="applyFilters" class="btn-filter">
                            Apply Filters
                        </button>
                        <button wire:click="resetFilters" class="btn-reset">
                            Reset
                        </button>
                    </div>
                </div>
            </div>

            <!-- Questions List -->
            <div class="questions-list">
                @if($selectedBank->questions->isEmpty())
                    <p class="no-questions">No questions found in this bank.</p>
                @else
                    <table class="questions-table">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Subject</th>
                                <th>Level</th>
                                <th>Year</th>
                                <th>Difficulty</th>
                                <th>Topic</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($selectedBank->questions as $question)
                                <tr>
                                    <td>{{ Str::limit($question->question_text, 50) }}</td>
                                    <td>{{ $question->subject?->name ?? '-' }}</td>
                                    <td>{{ $question->academicLevel?->name ?? '-' }}</td>
                                    <td>{{ $question->year?->name ?? '-' }}</td>
                                    <td>
                                        @if($question->difficulty_level)
                                            <div class="difficulty-stars">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="{{ $i <= $question->difficulty_level ? 'filled' : '' }}">★</span>
                                                @endfor
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $question->topic ?? '-' }}</td>
                                    <td>
                                        <button wire:click="editQuestion({{ $question->id }})">Edit</button>
                                        <button wire:click="deleteQuestion({{ $question->id }})">Delete</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endif

    <!-- Add Question Modal -->
    @if($showQuestionForm)
        <div class="modal-overlay">
            <div class="modal-content">
                <h3>Add New Question</h3>
                
                <form wire:submit.prevent="addQuestion">
                    <div class="form-group">
                        <label>Question Text</label>
                        <textarea wire:model="newQuestion.question_text" required></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Question Type</label>
                            <select wire:model="newQuestion.question_type_id" required>
                                <option value="">Select Type</option>
                                @foreach($questionTypes as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Points</label>
                            <input type="number" wire:model="newQuestion.points" min="1" value="1">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Subject</label>
                            <select wire:model="newQuestion.subject_id">
                                <option value="">Select Subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Academic Level</label>
                            <select wire:model="newQuestion.academic_level_id">
                                <option value="">Select Level</option>
                                @foreach($academicLevels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label>Year</label>
                            <select wire:model="newQuestion.year_id">
                                <option value="">Select Year</option>
                                @foreach($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Difficulty Level</label>
                            <select wire:model="newQuestion.difficulty_level">
                                <option value="">Select Difficulty</option>
                                <option value="1">Very Easy</option>
                                <option value="2">Easy</option>
                                <option value="3">Medium</option>
                                <option value="4">Hard</option>
                                <option value="5">Very Hard</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Topic</label>
                        <input type="text" wire:model="newQuestion.topic" placeholder="Enter topic...">
                    </div>
                    
                    @if(in_array($newQuestion['question_type_id'], [
                        \QuizPolls\App\Enums\QuestionType::MULTIPLE_CHOICE->value,
                        \QuizPolls\App\Enums\QuestionType::TRUE_FALSE->value
                    ]))
                        <div class="form-group">
                            <label>Options</label>
                            <div class="options-list">
                                @foreach($newQuestion['options'] as $index => $option)
                                    <div class="option-item">
                                        <input type="text" 
                                            wire:model="newQuestion.options.{{ $index }}.text" 
                                            placeholder="Option text">
                                        <label>
                                            <input type="checkbox" 
                                                wire:model="newQuestion.options.{{ $index }}.is_correct">
                                            Correct
                                        </label>
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
                    
                    <div class="form-group">
                        <label>Additional Metadata</label>
                        <div class="metadata-fields">
                            <div class="metadata-item">
                                <span>Bloom's Taxonomy Level</span>
                                <input type="text" wire:model="newQuestion.meta.blooms_taxonomy_level">
                            </div>
                            <div class="metadata-item">
                                <span>Learning Objective</span>
                                <input type="text" wire:model="newQuestion.meta.learning_objective">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="button" wire:click="showQuestionForm = false">Cancel</button>
                        <button type="submit">Save Question</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>