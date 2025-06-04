<!-- resources/views/livewire/exam-taker.blade.php -->
<div class="exam-container" x-data="{ showLeaveConfirm: false }">
    @if(!$examStarted && !$examCompleted)
        <div class="exam-start-screen">
            <h2 class="exam-title">{{ $exam->title }}</h2>
            <p class="exam-description">{{ $exam->description }}</p>
            
            <div class="exam-meta">
                <div class="meta-item">
                    <span class="meta-label">Total Time:</span>
                    <span class="meta-value">{{ $exam->total_time }} minutes</span>
                </div>
                @if($exam->passing_score)
                <div class="meta-item">
                    <span class="meta-label">Passing Score:</span>
                    <span class="meta-value">{{ $exam->passing_score }}%</span>
                </div>
                @endif
                <div class="meta-item">
                    <span class="meta-label">Sections:</span>
                    <span class="meta-value">{{ $exam->sections->count() }}</span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Questions:</span>
                    <span class="meta-value">{{ $exam->totalQuestions() }}</span>
                </div>
            </div>
            
            <div class="exam-instructions">
                <h3>Instructions:</h3>
                <ul>
                    <li>This exam contains {{ $exam->sections->count() }} sections</li>
                    <li>Total exam duration is {{ $exam->total_time }} minutes</li>
                    @foreach($exam->sections as $section)
                        <li>Section "{{ $section->title }}": 
                            {{ $section->questions->count() }} questions
                            @if($section->time_limit)
                                (Time limit: {{ $section->time_limit }} minutes)
                            @endif
                        </li>
                    @endforeach
                    @if($exam->shuffle_sections)
                        <li>Sections will appear in random order</li>
                    @endif
                    @if($exam->shuffle_questions)
                        <li>Questions will appear in random order within each section</li>
                    @endif
                    @if($exam->shuffle_options)
                        <li>Answer options will appear in random order</li>
                    @endif
                    @if($exam->require_proctoring)
                        <li>This exam requires proctoring</li>
                    @endif
                </ul>
            </div>
            
            <button wire:click="startExam" class="start-exam-btn">
                Start Exam
            </button>
        </div>
    @elseif($examStarted && !$examCompleted)
        <div class="exam-header">
            <div class="exam-progress">
                Section {{ $exam->sections->search($currentSection) + 1 }} of {{ $exam->sections->count() }}: 
                <strong>{{ $currentSection->title }}</strong>
            </div>
            
            <div class="exam-timers">
                @if($examTimeRemaining)
                <div class="timer exam-timer">
                    <i class="fas fa-clock"></i>
                    Exam Time: {{ floor($examTimeRemaining / 60) }}:{{ str_pad($examTimeRemaining % 60, 2, '0', STR_PAD_LEFT) }}
                </div>
                @endif
                
                @if(isset($sectionTimeRemaining[$currentSection->id]))
                <div class="timer section-timer">
                    <i class="fas fa-hourglass-half"></i>
                    Section Time: {{ floor($sectionTimeRemaining[$currentSection->id] / 60) }}:{{ str_pad($sectionTimeRemaining[$currentSection->id] % 60, 2, '0', STR_PAD_LEFT) }}
                </div>
                @endif
            </div>
        </div>
        
        <div class="section-container">
            <div class="section-description">
                {{ $currentSection->description }}
            </div>
            
            <div class="question-progress">
                Question {{ $currentQuestionIndex + 1 }} of {{ $currentSection->questions->count() }}
            </div>
            
            @php $question = $currentSection->questions[$currentQuestionIndex] @endphp
            
            <div class="question-container">
                <div class="question">
                    <h3 class="question-text">{{ $question->question_text }}</h3>
                    <p class="question-points">{{ $question->points }} point(s)</p>
                </div>
                
                <div class="options-container">
                    @if(in_array($question->question_type, ['multiple_choice', 'true_false']))
                        @foreach($question->options as $option)
                            <div class="option">
                                <input 
                                    type="{{ $question->question_type === 'multiple_choice' ? 'radio' : 'radio' }}"
                                    id="option-{{ $option->id }}"
                                    name="question-{{ $question->id }}"
                                    value="{{ $option->id }}"
                                    wire:model="userResponses.{{ $question->id }}.option_id"
                                    wire:change="saveResponse({{ $question->id }}, {{ $option->id }})"
                                >
                                <label for="option-{{ $option->id }}">{{ $option->option_text }}</label>
                            </div>
                        @endforeach
                    @elseif($question->question_type === 'short_answer')
                        <textarea 
                            class="short-answer-input"
                            wire:model.defer="userResponses.{{ $question->id }}.answer_text"
                            wire:change="saveResponse({{ $question->id }}, null, $event.target.value)"
                            placeholder="Type your answer here..."
                        ></textarea>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="exam-navigation">
            @if($currentQuestionIndex > 0)
                <button wire:click="previousQuestion" class="nav-btn prev-btn">
                    Previous Question
                </button>
            @else
                <div class="nav-spacer"></div>
            @endif
            
            @if($currentQuestionIndex < $currentSection->questions->count() - 1)
                <button wire:click="nextQuestion" class="nav-btn next-btn">
                    Next Question
                </button>
            @else
                <button 
                    @click="showLeaveConfirm = true" 
                    class="nav-btn next-section-btn"
                >
                    {{ $exam->sections->last()->id === $currentSection->id ? 'Submit Exam' : 'Next Section' }}
                </button>
                
                <div 
                    x-show="showLeaveConfirm" 
                    class="confirmation-modal"
                    @click.away="showLeaveConfirm = false"
                >
                    <div class="confirmation-content">
                        <h3>Are you sure?</h3>
                        <p>You won't be able to return to this section after proceeding.</p>
                        <div class="confirmation-buttons">
                            <button @click="showLeaveConfirm = false" class="cancel-btn">
                                Cancel
                            </button>
                            <button wire:click="nextSection" class="confirm-btn">
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        
        <script>
            // Timer countdown
            setInterval(() => {
                Livewire.emit('timerTick');
            }, 1000);
        </script>
    @elseif($examCompleted)
        <div class="exam-complete-message">
            <h2>Exam Submitted Successfully!</h2>
            
            @if($exam->show_score_after)
                <div class="exam-result-summary">
                    <p>Your score: <strong>{{ $attempt->score }} / {{ $attempt->total_score }}</strong></p>
                    @if($exam->passing_score)
                        <p>Result: 
                            <strong class="{{ $attempt->passed ? 'text-success' : 'text-danger' }}">
                                {{ $attempt->passed ? 'PASSED' : 'FAILED' }}
                            </strong>
                        </p>
                    @endif
                </div>
            @endif
            
            <a href="{{ route('exam.results', $attempt->id) }}" class="view-results-btn">
                View Detailed Results
            </a>
        </div>
    @endif
</div>