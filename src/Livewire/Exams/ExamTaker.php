<?php
namespace Sosupp\Questionable\Livewire\Exams;

use Livewire\Component;
use Sosupp\Questionable\Models\Exam;
use Sosupp\Questionable\Services\ExamService;
use Sosupp\Questionable\Models\ExamAttemptSection;

class ExamTaker extends Component
{
    public $exam;
    public $attempt;
    public $currentSection;
    public $currentQuestionIndex = 0;
    public $userResponses = [];
    public $sectionTimeRemaining = [];
    public $examTimeRemaining;
    public $examStarted = false;
    public $examCompleted = false;
    public $showConfirmation = false;

    protected $listeners = [
        'timerTick' => 'decrementTime',
        'saveResponse' => 'saveResponse',
    ];

    public function mount($examId)
    {
        $this->exam = Exam::with(['sections.questions.options'])->findOrFail($examId);
        
        // Initialize responses array
        foreach ($this->exam->sections as $section) {
            foreach ($section->questions as $question) {
                $this->userResponses[$question->id] = [
                    'option_id' => null,
                    'answer_text' => null,
                ];
            }
        }
    }

    public function startExam()
    {
        $this->attempt = app(ExamService::class)->startExamAttempt($this->exam, auth()->user());
        
        // Start first section
        $this->currentSection = $this->exam->sections->first();
        $this->startSection($this->currentSection);
        
        $this->examStarted = true;
        
        // Set time remaining for exam
        if ($this->exam->total_time) {
            $this->examTimeRemaining = $this->exam->total_time * 60;
        }
    }

    public function startSection($section)
    {
        $this->currentSection = $section;
        $this->currentQuestionIndex = 0;
        
        // Record section attempt
        ExamAttemptSection::create([
            'attempt_id' => $this->attempt->id,
            'exam_section_id' => $section->id,
            'started_at' => now(),
        ]);
        
        // Set time remaining for section
        if ($section->time_limit) {
            $this->sectionTimeRemaining[$section->id] = $section->time_limit * 60;
        }
    }

    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < $this->currentSection->questions->count() - 1) {
            $this->currentQuestionIndex++;
        }
    }

    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }

    public function nextSection()
    {
        $currentIndex = $this->exam->sections->search(function($section) {
            return $section->id === $this->currentSection->id;
        });
        
        if ($currentIndex < $this->exam->sections->count() - 1) {
            $this->completeCurrentSection();
            $this->startSection($this->exam->sections[$currentIndex + 1]);
        } else {
            $this->submitExam();
        }
    }

    public function completeCurrentSection()
    {
        // Mark section as completed
        $this->attempt->sections()
            ->where('exam_section_id', $this->currentSection->id)
            ->update(['completed_at' => now()]);
    }

    public function saveResponse($questionId, $optionId = null, $answerText = null)
    {
        $this->userResponses[$questionId] = [
            'option_id' => $optionId,
            'answer_text' => $answerText,
        ];
    }

    public function decrementTime()
    {
        // Decrement exam time
        if ($this->examTimeRemaining > 0) {
            $this->examTimeRemaining--;
            
            if ($this->examTimeRemaining === 0) {
                $this->submitExam();
                return;
            }
        }
        
        // Decrement section time
        if (isset($this->sectionTimeRemaining[$this->currentSection->id])) {
            $this->sectionTimeRemaining[$this->currentSection->id]--;
            
            if ($this->sectionTimeRemaining[$this->currentSection->id] === 0) {
                $this->nextSection();
            }
        }
    }

    public function submitExam()
    {
        if ($this->examCompleted) {
            return;
        }

        // Complete current section if not already
        $this->completeCurrentSection();
        
        // Save all responses
        foreach ($this->userResponses as $questionId => $response) {
            $question = $this->exam->questions()->find($questionId);
            $isCorrect = false;
            $pointsEarned = 0;
            
            if ($question->question_type === 'multiple_choice' || $question->question_type === 'true_false') {
                $selectedOption = $question->options->find($response['option_id']);
                $isCorrect = $selectedOption ? $selectedOption->is_correct : false;
                $pointsEarned = $isCorrect ? $question->points : 0;
            } elseif ($question->question_type === 'short_answer') {
                $correctOptions = $question->correctOptions;
                $isCorrect = $correctOptions->contains('option_text', strtolower(trim($response['answer_text'])));
                $pointsEarned = $isCorrect ? $question->points : 0;
            }
            
            $this->attempt->responses()->create([
                'question_id' => $questionId,
                'option_id' => $response['option_id'],
                'answer_text' => $response['answer_text'],
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ]);
        }
        
        // Calculate final results
        $this->attempt->calculateResults();
        
        $this->examCompleted = true;
        $this->emit('examCompleted', $this->attempt->id);
    }

    public function render()
    {
        return view('questionable::livewire.exams.exam-taker');
    }


}