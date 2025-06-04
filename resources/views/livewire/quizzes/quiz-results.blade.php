<!-- resources/views/livewire/quiz-results.blade.php -->
<div class="quiz-results-container">
    <h2 class="results-title">Quiz Results: {{ $quiz->title }}</h2>
    
    <div class="results-summary">
        <div class="score-card">
            <div class="score-circle" style="--percentage: {{ $scorePercentage }}%">
                <span class="score-text">{{ $scorePercentage }}%</span>
            </div>
            <div class="score-details">
                <p><strong>Score:</strong> {{ $attempt->score }} / {{ $attempt->total_questions }}</p>
                <p><strong>Completed:</strong> {{ $attempt->completed_at->format('M j, Y g:i A') }}</p>
                <p><strong>Time Taken:</strong> 
                    {{ $attempt->completed_at->diffInMinutes($attempt->started_at) }} minutes
                </p>
            </div>
        </div>
    </div>
    
    <div class="results-details">
        <h3>Question Breakdown</h3>
        
        @foreach($responses as $response)
            <div class="question-result {{ $response->is_correct ? 'correct' : 'incorrect' }}">
                <div class="question-header">
                    <h4>{{ $response->question->question_text }}</h4>
                    <span class="points">
                        {{ $response->points_earned }} / {{ $response->question->points }} points
                    </span>
                </div>
                
                <div class="user-answer">
                    <strong>Your Answer:</strong> 
                    @if($response->question->question_type === 'multiple_choice' || $response->question->question_type === 'true_false')
                        {{ $response->option ? $response->option->option_text : 'No answer provided' }}
                    @else
                        {{ $response->answer_text ?: 'No answer provided' }}
                    @endif
                </div>
                
                @if(!$response->is_correct && $response->question->correctOptions->count() > 0)
                    <div class="correct-answer">
                        <strong>Correct Answer:</strong> 
                        @if($response->question->question_type === 'multiple_choice' || $response->question->question_type === 'true_false')
                            {{ $response->question->correctOptions->pluck('option_text')->join(', ') }}
                        @else
                            {{ $response->question->correctOptions->first()->option_text }}
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    
    <div class="results-actions">
        <a href="{{ route('quiz.list') }}" class="back-to-quizzes-btn">
            Back to Quizzes
        </a>
    </div>
</div>