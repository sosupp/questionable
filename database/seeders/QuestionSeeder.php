<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sosupp\Questionable\Models\Year;
use Sosupp\Questionable\Models\Subject;
use Sosupp\Questionable\Enums\QuestionType;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\AcademicLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $banks = QuestionBank::all();
        $subjects = Subject::all();
        $levels = AcademicLevel::all();
        $years = Year::all();
        $currentYear = Year::where('is_current', true)->first();

        $questionData = [
            // Mathematics Questions
            [
                'question_text' => 'What is 2 + 2?',
                'question_type_id' => QuestionType::MULTIPLE_CHOICE->value,
                'subject_id' => $subjects->where('code', 'MATH')->first()->id,
                'academic_level_id' => $levels->where('code', 'P1')->first()->id,
                'year_id' => $currentYear->id,
                'difficulty_level' => 1,
                'topic' => 'Basic Arithmetic',
                'points' => 1,
                'options' => [
                    ['text' => '3', 'is_correct' => false],
                    ['text' => '4', 'is_correct' => true],
                    ['text' => '5', 'is_correct' => false],
                ],
                'meta' => [
                    'blooms_taxonomy_level' => 'Remembering',
                    'learning_objective' => 'Perform basic addition',
                ]
            ],
            [
                'question_text' => 'Solve for x: 2x + 5 = 15',
                'question_type_id' => QuestionType::SHORT_ANSWER->value,
                'subject_id' => $subjects->where('code', 'MATH')->first()->id,
                'academic_level_id' => $levels->where('code', 'J1')->first()->id,
                'year_id' => $currentYear->id,
                'difficulty_level' => 2,
                'topic' => 'Linear Equations',
                'points' => 2,
                'options' => [
                    ['text' => '5', 'is_correct' => true],
                ],
                'meta' => [
                    'blooms_taxonomy_level' => 'Applying',
                    'learning_objective' => 'Solve simple linear equations',
                ]
            ],
            // English Questions
            [
                'question_text' => 'Which of these is a noun?',
                'question_type_id' => QuestionType::MULTIPLE_CHOICE->value,
                'subject_id' => $subjects->where('code', 'ENG')->first()->id,
                'academic_level_id' => $levels->where('code', 'P3')->first()->id,
                'year_id' => $currentYear->id,
                'difficulty_level' => 1,
                'topic' => 'Parts of Speech',
                'points' => 1,
                'options' => [
                    ['text' => 'Run', 'is_correct' => false],
                    ['text' => 'Beautiful', 'is_correct' => false],
                    ['text' => 'Dog', 'is_correct' => true],
                ],
                'meta' => [
                    'blooms_taxonomy_level' => 'Understanding',
                    'learning_objective' => 'Identify nouns',
                ]
            ],
            // Science Questions
            [
                'question_text' => 'The process by which plants make their food is called:',
                'question_type_id' => QuestionType::MULTIPLE_CHOICE->value,
                'subject_id' => $subjects->where('code', 'BIO')->first()->id,
                'academic_level_id' => $levels->where('code', 'J2')->first()->id,
                'year_id' => $currentYear->id,
                'difficulty_level' => 2,
                'topic' => 'Photosynthesis',
                'points' => 1,
                'options' => [
                    ['text' => 'Respiration', 'is_correct' => false],
                    ['text' => 'Photosynthesis', 'is_correct' => true],
                    ['text' => 'Transpiration', 'is_correct' => false],
                ],
                'meta' => [
                    'blooms_taxonomy_level' => 'Remembering',
                    'learning_objective' => 'Define photosynthesis',
                ]
            ],
            // Add more questions as needed...
            [
                'question_text' => 'What is the capital of France?',
                'question_type_id' => QuestionType::MULTIPLE_CHOICE->value,
                'subject_id' => $subjects->where('code', 'GEO')->first()->id,
                'academic_level_id' => $levels->where('code', 'J1')->first()->id,
                'year_id' => $currentYear->id,
                'difficulty_level' => 1,
                'topic' => 'European Capitals',
                'points' => 1,
                'options' => [
                    ['text' => 'London', 'is_correct' => false],
                    ['text' => 'Paris', 'is_correct' => true],
                    ['text' => 'Berlin', 'is_correct' => false],
                ],
                'meta' => [
                    'blooms_taxonomy_level' => 'Remembering',
                    'learning_objective' => 'Identify European capitals',
                ]
            ],
            [
                'question_text' => 'Explain Newton\'s First Law of Motion',
                'question_type_id' => QuestionType::SHORT_ANSWER->value,
                'subject_id' => $subjects->where('code', 'PHY')->first()->id,
                'academic_level_id' => $levels->where('code', 'S2')->first()->id,
                'year_id' => $currentYear->id,
                'difficulty_level' => 3,
                'topic' => 'Laws of Motion',
                'points' => 3,
                'options' => [
                    ['text' => 'An object in motion stays in motion unless acted upon by an external force', 'is_correct' => true],
                ],
                'meta' => [
                    'blooms_taxonomy_level' => 'Understanding',
                    'learning_objective' => 'Explain Newton\'s Laws',
                ]
            ],
        ];

        foreach ($banks as $bank) {
            foreach ($questionData as $data) {
                // Only add subject-relevant questions to subject-specific banks
                if ($bank->name === 'General Knowledge Bank' || 
                    str_contains($bank->name, $subjects->find($data['subject_id'])->name)) {
                    
                    $question = $bank->questions()->create([
                        'question_type_id' => $data['question_type_id'],
                        'question_text' => $data['question_text'],
                        'subject_id' => $data['subject_id'],
                        'academic_level_id' => $data['academic_level_id'],
                        'year_id' => $data['year_id'],
                        'difficulty_level' => $data['difficulty_level'],
                        'topic' => $data['topic'],
                        'points' => $data['points'],
                    ]);
                    
                    // Add options if provided
                    if (isset($data['options'])) {
                        foreach ($data['options'] as $option) {
                            $question->options()->create([
                                'option_text' => $option['text'],
                                'is_correct' => $option['is_correct'],
                            ]);
                        }
                    }
                    
                    // Add metadata if provided
                    if (isset($data['meta'])) {
                        foreach ($data['meta'] as $key => $value) {
                            $question->meta()->create([
                                'key' => $key,
                                'value' => $value,
                            ]);
                        }
                    }
                }
            } 
        }




    }
}
