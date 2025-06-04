<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Sosupp\Questionable\Models\Exam;
use Sosupp\Questionable\Models\Poll;
use Sosupp\Questionable\Models\Quiz;
use Sosupp\Questionable\Models\Question;
use Sosupp\Questionable\Enums\QuestionType;
use Sosupp\Questionable\Models\AcademicLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = App\Models\User::first();
        $mathQuestions = Question::whereHas('subject', function($q) {
            $q->where('code', 'MATH');
        })->get();
        
        $scienceQuestions = Question::whereHas('subject', function($q) {
            $q->whereIn('code', ['PHY', 'CHEM', 'BIO']);
        })->get();
        
        $sss1Level = AcademicLevel::where('code', 'S1')->first();
        $currentYear = Carbon::now()->year;
        
        // Create a Quiz
        $quiz = Quiz::create([
            'title' => 'Basic Math Quiz',
            'description' => 'A simple quiz to test basic math knowledge',
            'time_limit' => 30,
            'is_active' => true,
            'shuffle_questions' => true,
            'show_correct_answers' => true,
        ]);
        
        // Attach math questions to the quiz
        $quiz->questions()->attach(
            $mathQuestions->where('academic_level_id', $sss1Level->id)
                ->take(10)
                ->pluck('id')
                ->toArray()
        );

        // Create a Poll
        $poll = Poll::create([
            'title' => 'Student Learning Preferences',
            'description' => 'Survey about how students prefer to learn',
            'is_anonymous' => true,
            'is_active' => true,
        ]);

        // Create poll questions (would need to add some poll-specific questions)
        $pollQuestions = [
            [
                'question_text' => 'Which learning method do you prefer?',
                'question_type_id' => QuestionType::MULTIPLE_CHOICE->value,
                'options' => [
                    ['text' => 'Visual (videos, diagrams)', 'is_correct' => false],
                    ['text' => 'Auditory (lectures, discussions)', 'is_correct' => false],
                    ['text' => 'Reading/Writing', 'is_correct' => false],
                    ['text' => 'Hands-on activities', 'is_correct' => false],
                ]
            ],
            // Add more poll questions...
        ];

        foreach ($pollQuestions as $pollQuestionData) {
            $question = $poll->questions()->create([
                'question_text' => $pollQuestionData['question_text'],
                'question_type_id' => $pollQuestionData['question_type_id'],
            ]);
            
            foreach ($pollQuestionData['options'] as $option) {
                $question->options()->create([
                    'option_text' => $option['text'],
                    'is_correct' => $option['is_correct'],
                ]);
            }
        }

        // Create an Exam
        $exam = Exam::create([
            'title' => 'SSS 1 Mathematics End-of-Term Exam',
            'description' => 'Comprehensive exam covering all topics from the term',
            'total_time' => 120,
            'passing_score' => 50,
            'max_attempts' => 2,
            'require_proctoring' => false,
            'show_score_after' => true,
            'show_answers_after' => false,
            'available_from' => Carbon::now()->subDays(1),
            'available_to' => Carbon::now()->addDays(7),
        ]);

        // Add sections to the exam
        $sections = [
            [
                'title' => 'Multiple Choice',
                'description' => 'Answer all multiple choice questions',
                'time_limit' => 45,
                'questions' => $mathQuestions
                    ->where('question_type_id', QuestionType::MULTIPLE_CHOICE->value)
                    ->where('academic_level_id', $sss1Level->id)
                    ->take(20)
                    ->pluck('id')
                    ->toArray()
            ],
            [
                'title' => 'Problem Solving',
                'description' => 'Show your work for these problems',
                'time_limit' => 75,
                'questions' => $mathQuestions
                    ->where('question_type_id', QuestionType::SHORT_ANSWER->value)
                    ->where('academic_level_id', $sss1Level->id)
                    ->take(5)
                    ->pluck('id')
                    ->toArray()
            ],
        ];

        foreach ($sections as $sectionData) {
            $section = $exam->sections()->create([
                'title' => $sectionData['title'],
                'description' => $sectionData['description'],
                'time_limit' => $sectionData['time_limit'],
            ]);
            
            $section->questions()->attach($sectionData['questions']);
        }

        // Create a comprehensive science exam
        $scienceExam = Exam::create([
            'title' => 'Science Olympiad',
            'description' => 'Annual science competition exam',
            'total_time' => 180,
            'passing_score' => 70,
            'max_attempts' => 1,
            'require_proctoring' => true,
            'show_score_after' => false,
            'show_answers_after' => false,
            'available_from' => Carbon::now()->addDays(3),
            'available_to' => Carbon::now()->addDays(10),
        ]);

        $scienceExamSections = [
            [
                'title' => 'Physics',
                'time_limit' => 60,
                'questions' => $scienceQuestions
                    ->where('subject.code', 'PHY')
                    ->take(15)
                    ->pluck('id')
                    ->toArray()
            ],
            [
                'title' => 'Chemistry',
                'time_limit' => 60,
                'questions' => $scienceQuestions
                    ->where('subject.code', 'CHEM')
                    ->take(15)
                    ->pluck('id')
                    ->toArray()
            ],
            [
                'title' => 'Biology',
                'time_limit' => 60,
                'questions' => $scienceQuestions
                    ->where('subject.code', 'BIO')
                    ->take(15)
                    ->pluck('id')
                    ->toArray()
            ],
        ];

        foreach ($scienceExamSections as $section) {
            $scienceExam->sections()->create($section);
        }



    }
}
