<!-- resources/views/livewire/quiz-list.blade.php -->
<div class="quiz-list-container">
    <h2 class="quiz-list-title">Available Quizzes</h2>
    
    @if($quizzes->isEmpty())
        <p class="no-quizzes-message">No quizzes available at the moment.</p>
    @else
        <div class="quiz-grid">
            @foreach($quizzes as $quiz)
                <div class="quiz-card">
                    <h3 class="quiz-title">{{ $quiz->title }}</h3>
                    <p class="quiz-description">{{ $quiz->description }}</p>
                    <div class="quiz-meta">
                        <span class="time-limit">
                            <i class="fas fa-clock"></i> 
                            {{ $quiz->time_limit ? $quiz->time_limit . ' minutes' : 'No time limit' }}
                        </span>
                        <span class="questions-count">
                            <i class="fas fa-question-circle"></i> 
                            {{ $quiz->questions->count() }} questions
                        </span>
                    </div>
                    <a href="{{ route('quiz.take', $quiz->id) }}" class="start-quiz-btn">
                        Start Quiz
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>