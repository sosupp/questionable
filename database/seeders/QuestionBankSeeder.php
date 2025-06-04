<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Sosupp\Questionable\Models\QuestionBank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionBankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

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
            QuestionBank::create($bank);
        }
    }
}
