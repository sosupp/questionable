<?php
namespace Sosupp\Questionable\Livewire\Exams;

use Livewire\Component;
use Sosupp\Questionable\Models\Exam;
use Sosupp\Questionable\Models\Year;
use Sosupp\Questionable\Models\Subject;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\AcademicLevel;
use Sosupp\Questionable\Services\ExamService;

class ExamManager extends Component
{
    public $exams;
    public $selectedExam;
    public $questionBanks;
    public $subjects;
    public $academicLevels;
    public $years;
    public $showExamForm = false;
    public $showSectionForm = false;
    public $currentSection = null;
    
    public $newExam = [
        'title' => '',
        'description' => '',
        'total_time' => 120,
        'passing_score' => null,
        'max_attempts' => 1,
        'shuffle_sections' => false,
        'shuffle_questions' => false,
        'shuffle_options' => false,
        'require_proctoring' => false,
        'show_score_after' => false,
        'show_answers_after' => false,
        'available_from' => null,
        'available_to' => null,
    ];

    public $newSection = [
        'title' => '',
        'description' => '',
        'time_limit' => null,
        'questions' => [],
    ];

    protected $rules = [
        'newExam.title' => 'required|string|max:255',
        'newExam.total_time' => 'required|integer|min:1',
        'newSection.title' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->exams = Exam::with('sections.questions')->get();
        $this->questionBanks = QuestionBank::with('questions')->get();
        $this->subjects = Subject::orderBy('name')->get();
        $this->academicLevels = AcademicLevel::orderBy('order')->get();
        $this->years = Year::orderBy('start_year', 'desc')->get();
    }


    public function createExam()
    {
        $this->validate([
            'newExam.title' => 'required',
            'newExam.total_time' => 'required|integer|min:1',
        ]);

        $exam = Exam::create($this->newExam);
        
        if (!empty($this->newSection['questions'])) {
            $section = $exam->sections()->create($this->newSection);
            $section->questions()->attach($this->newSection['questions']);
        }
        
        $this->reset(['newExam', 'newSection']);
        $this->showExamForm = false;
        $this->exams->push($exam);
        $this->emit('notify', 'Exam created successfully!');
    }

    public function addSection()
    {
        $this->validate([
            'newSection.title' => 'required',
        ]);

        $section = $this->selectedExam->sections()->create($this->newSection);
        
        if (!empty($this->newSection['questions'])) {
            $section->questions()->attach($this->newSection['questions']);
        }

        $this->reset('newSection');
        $this->showSectionForm = false;
        $this->selectedExam->refresh();
        $this->emit('notify', 'Section added successfully!');
    }


    public function selectExam($examId)
    {
        $this->selectedExam = Exam::with('sections.questions')->find($examId);
        $this->showExamForm = false;
        $this->showSectionForm = false;
    }

    public function deleteExam($examId)
    {
        Exam::find($examId)->delete();
        $this->exams = $this->exams->filter(fn($exam) => $exam->id != $examId);
        $this->emit('notify', 'Exam deleted successfully!');
    }
    
    public function deleteSection($sectionId)
    {
        $this->selectedExam->sections()->find($sectionId)->delete();
        $this->selectedExam->refresh();
        $this->emit('notify', 'Section deleted successfully!');
    }

    public function render()
    {
        return view('questionable::livewire.exams.exam-manager');
    }


}
