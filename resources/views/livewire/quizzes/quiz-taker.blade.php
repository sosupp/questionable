<!-- resources/views/livewire/take-quiz.blade.php -->
<div class="quiz-container" x-data="{ showConfirmation: false }">
    @if(!$quizStarted && !$quizCompleted)
        <div class="quiz-start-screen">
            <h2 class="quiz-title">{{ $quiz->title }}</h2>
            <p class="quiz-description">{{ $quiz->description }}</p>
            
            <div class="quiz-instructions">
                <h3>Instructions:</h3>
                <ul>
                    <li>This quiz contains {{ $quiz->questions->count() }} questions</li>
                    @if($quiz->time_limit)
                        <li>Time limit: {{ $quiz->time_limit }} minutes</li>
                    @else
                        <li>No time limit</li>
                    @endif
                    <li>Read each question carefully before answering</li>
                    <li>You can navigate between questions using the Previous/Next buttons</li>
                    <li>Once submitted, you cannot change your answers</li>
                </ul>
            </div>
            
            <button wire:click="startQuiz" class="start-quiz-btn">
                Start Quiz
            </button>
        </div>
    @elseif($quizStarted && !$quizCompleted)
        <div class="quiz-progress">
            <div class="progress-bar">
                <div class="progress-fill" 
                     style="width: {{ ($currentQuestionIndex + 1) / $questions->count() * 100 }}%"></div>
            </div>
            <div class="progress-text">
                Question {{ $currentQuestionIndex + 1 }} of {{ $questions->count() }}
            </div>
            
            @if($timeRemaining)
                <div class="timer">
                    <i class="fas fa-clock"></i>
                    {{ floor($timeRemaining / 60) }}:{{ str_pad($timeRemaining % 60, 2, '0', STR_PAD_LEFT) }}
                </div>
            @endif
        </div>
        
        <div class="quiz-question-container">
            @php $question = $questions[$currentQuestionIndex] @endphp
            
            <div class="question">
                <h3 class="question-text">{{ $question->question_text }}</h3>
                <p class="question-points">{{ $question->points }} point(s)</p>
            </div>
            
            <div class="options-container">
                @if($question->question_type === 'multiple_choice' || $question->question_type === 'true_false')
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
        
        <div class="quiz-navigation">
            @if($currentQuestionIndex > 0)
                <button wire:click="previousQuestion" class="nav-btn prev-btn">
                    Previous
                </button>
            @else
                <div class="nav-spacer"></div>
            @endif
            
            @if($currentQuestionIndex < $questions->count() - 1)
                <button wire:click="nextQuestion" class="nav-btn next-btn">
                    Next
                </button>
            @else
                <button 
                    @click="showConfirmation = true" 
                    class="nav-btn submit-btn"
                >
                    Submit Quiz
                </button>
                
                <div 
                    x-show="showConfirmation" 
                    class="confirmation-modal"
                    @click.away="showConfirmation = false"
                >
                    <div class="confirmation-content">
                        <h3>Are you sure you want to submit?</h3>
                        <p>You won't be able to change your answers after submission.</p>
                        <div class="confirmation-buttons">
                            <button @click="showConfirmation = false" class="cancel-btn">
                                Cancel
                            </button>
                            <button wire:click="submitQuiz" class="confirm-btn">
                                Confirm Submission
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @elseif($quizCompleted)
        <div class="quiz-complete-message">
            <h2>Quiz Submitted Successfully!</h2>
            <p>Your results are being calculated. You can view your results now.</p>
            <a href="{{ route('quiz.results', $attemptId) }}" class="view-results-btn">
                View Results
            </a>
        </div>
    @endif
    
    @if($timeRemaining)
        <script>
            // Timer countdown
            setInterval(() => {
                Livewire.emit('timerTick');
            }, 1000);
        </script>
    @endif
</div>