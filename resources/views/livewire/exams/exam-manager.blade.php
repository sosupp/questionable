<!-- resources/views/livewire/exam-manager.blade.php -->
<div class="exam-manager-container">
    <div class="header">
        <h2>Exam Manager</h2>
        <button wire:click="$toggle('showExamForm')" class="btn-primary">
            {{ $showExamForm ? 'Cancel' : '+ New Exam' }}
        </button>
    </div>

    @if($showExamForm)
        <div class="exam-form">
            <h3>Create New Exam</h3>
            
            <div class="form-grid">
                <div class="form-group">
                    <label>Exam Title</label>
                    <input type="text" wire:model="newExam.title" required>
                </div>
                
                <div class="form-group">
                    <label>Description</label>
                    <textarea wire:model="newExam.description"></textarea>
                </div>
                
                <div class="form-group">
                    <label>Total Time (minutes)</label>
                    <input type="number" wire:model="newExam.total_time" min="1" required>
                </div>
                
                <div class="form-group">
                    <label>Passing Score (%)</label>
                    <input type="number" wire:model="newExam.passing_score" min="0" max="100">
                </div>
                
                <div class="form-group">
                    <label>Max Attempts</label>
                    <input type="number" wire:model="newExam.max_attempts" min="1">
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newExam.shuffle_sections">
                        Shuffle Sections
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newExam.shuffle_questions">
                        Shuffle Questions
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newExam.shuffle_options">
                        Shuffle Options
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newExam.require_proctoring">
                        Require Proctoring
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newExam.show_score_after">
                        Show Score After
                    </label>
                </div>
                
                <div class="form-group">
                    <label>
                        <input type="checkbox" wire:model="newExam.show_answers_after">
                        Show Answers After
                    </label>
                </div>
                
                <div class="form-group">
                    <label>Available From</label>
                    <input type="datetime-local" wire:model="newExam.available_from">
                </div>
                
                <div class="form-group">
                    <label>Available To</label>
                    <input type="datetime-local" wire:model="newExam.available_to">
                </div>
            </div>
            
            <div class="form-actions">
                <button wire:click="createExam" class="btn-submit">Create Exam</button>
            </div>
        </div>
    @endif

    @if($selectedExam && !$showExamForm)
        <div class="exam-details">
            <div class="exam-header">
                <h3>{{ $selectedExam->title }}</h3>
                <p>{{ $selectedExam->description }}</p>
                
                <div class="exam-meta">
                    <span>Time: {{ $selectedExam->total_time }} mins</span>
                    <span>Passing: {{ $selectedExam->passing_score ?? 'N/A' }}%</span>
                    <span>Attempts: {{ $selectedExam->max_attempts }}</span>
                </div>
                
                <div class="exam-actions">
                    <button wire:click="$toggle('showSectionForm')" class="btn-primary">
                        {{ $showSectionForm ? 'Cancel' : '+ Add Section' }}
                    </button>
                    <button wire:click="deleteExam({{ $selectedExam->id }})" class="btn-danger">
                        Delete Exam
                    </button>
                </div>
            </div>
            
            @if($showSectionForm)
                <div class="section-form">
                    <h4>Add New Section</h4>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>Section Title</label>
                            <input type="text" wire:model="newSection.title" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Description</label>
                            <textarea wire:model="newSection.description"></textarea>
                        </div>
                        
                        <div class="form-group">
                            <label>Time Limit (minutes)</label>
                            <input type="number" wire:model="newSection.time_limit" min="1">
                        </div>
                    </div>
                    
                    <div class="questions-selection">
                        <h5>Select Questions</h5>
                        <div class="questions-grid">
                            @foreach($questionBanks as $bank)
                                <div class="bank-questions">
                                    <h6>{{ $bank->name }}</h6>
                                    @foreach($bank->questions as $question)
                                        <label>
                                            <input type="checkbox" 
                                                wire:model="newSection.questions"
                                                value="{{ $question->id }}">
                                            {{ Str::limit($question->question_text, 50) }}
                                        </label>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button wire:click="addSection" class="btn-submit">Add Section</button>
                    </div>
                </div>
            @endif
            
            <div class="sections-list">
                <h4>Sections</h4>
                
                @if($selectedExam->sections->isEmpty())
                    <p>No sections added yet.</p>
                @else
                    @foreach($selectedExam->sections as $section)
                        <div class="section-card">
                            <div class="section-header">
                                <h5>{{ $section->title }}</h5>
                                <p>{{ $section->description }}</p>
                                <div class="section-meta">
                                    <span>Questions: {{ $section->questions->count() }}</span>
                                    @if($section->time_limit)
                                        <span>Time: {{ $section->time_limit }} mins</span>
                                    @endif
                                </div>
                                <button wire:click="deleteSection({{ $section->id }})" class="btn-danger">
                                    Delete Section
                                </button>
                            </div>
                            
                            <div class="questions-list">
                                @foreach($section->questions as $question)
                                    <div class="question-item">
                                        <p>{{ $question->question_text }}</p>
                                        <span class="question-meta">
                                            {{ $question->subject->name }} | 
                                            {{ $question->academicLevel->name }} | 
                                            Difficulty: {{ $question->difficulty_level }}/5
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @elseif(!$showExamForm)
        <div class="exams-list">
            @if($exams->isEmpty())
                <p>No exams created yet.</p>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Sections</th>
                            <th>Questions</th>
                            <th>Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($exams as $exam)
                            <tr wire:click="selectExam({{ $exam->id }})" class="{{ $selectedExam && $selectedExam->id === $exam->id ? 'active' : '' }}">
                                <td>{{ $exam->title }}</td>
                                <td>{{ $exam->sections->count() }}</td>
                                <td>{{ $exam->sections->sum(fn($section) => $section->questions->count()) }}</td>
                                <td>{{ $exam->total_time }} mins</td>
                                <td>
                                    <button wire:click.stop="deleteExam({{ $exam->id }})" class="btn-danger">
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