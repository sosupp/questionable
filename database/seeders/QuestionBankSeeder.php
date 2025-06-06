<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sosupp\Questionable\Models\QuestionBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Sosupp\Questionable\Models\QuestionType;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = \App\Models\User::first();

        // Banks
        $banks = [
            [
                'name' => 'General Knowledge Bank',
                'description' => 'Questions covering various subjects',
                'owner_id' => $user->id,
                'owner_type' => get_class($user)
            ],
            [
                'name' => 'Science Question Bank',
                'description' => 'Questions for science subjects',
                'owner_id' => $user->id,
                'owner_type' => get_class($user)
            ],
            [
                'name' => 'Mathematics Question Bank',
                'description' => 'Math questions for all levels',
                'owner_id' => $user->id,
                'owner_type' => get_class($user)
            ],
            [
                'name' => 'History Question Bank',
                'description' => 'Historical facts and events',
                'owner_id' => $user->id,
                'owner_type' => get_class($user)
            ],
        ];

        foreach ($banks as $bank) {
            QuestionBank::updateOrCreate(
                [
                    'name' => $bank['name']
                ],
                [
                    'slug' => str($bank['name'])->slug()->value(),
                    'description' => $bank['name'],
                    'owner_id' => $bank['owner_id'],
                    'owner_type' => $bank['owner_type']
                ]
            );
        }

        // Question Types
        $questionTypes = [
            [
                'name' => 'Multiple Choice',
                'description' => 'Question with multiple predefined answers, one or more may be correct',

            ],
            [
                'name' => 'True/False',
                'description' => 'Question with only true or false as possible answers',
            ],
            [
                'name' => 'Short Answer',
                'description' => 'Question requiring a brief text response',
            ],
            [
                'name' => 'Essay',
                'description' => 'Question requiring a longer, detailed written response',
            ],
            [
                'name' => 'Matching',
                'description' => 'Question requiring matching items from two columns',
            ],
            [
                'name' => 'Fill the Blank',
                'description' => 'Question with blanks to be filled in the text',
            ],
            [
                'name' => 'Rating Scale',
                'description' => 'Question requiring selection on a rating scale',
            ],
            [
                'name' => 'Ranking',
                'description' => 'Question requiring ordering/ranking of items',
            ]
        ];

        foreach ($questionTypes as $questionType) {
            QuestionType::updateOrCreate(
                [
                    'name' => $questionType['name']
                ],
                [
                    'slug' => str($questionType['name'])->slug()->value(),
                    'description' => $questionType['description'],
                ]
            );
        }

    }
}
