<?php

return [
    'models' => [
        'question_bank' => Sosupp\Questionable\Models\QuestionBank::class,
        'question' => Sosupp\Questionable\Models\Question::class,
        'quiz' => Sosupp\Questionable\Models\Quiz::class,
        'poll' => Sosupp\Questionable\Models\Poll::class,
    ],
    
    'routes' => [
        'prefix' => 'quiz-polls',
        'middleware' => ['web', 'auth'],
    ],
    
    'question_types' => [
        'multiple_choice' => [
            'gradable' => true,
            'has_options' => true,
        ],
        'true_false' => [
            'gradable' => true,
            'has_options' => true,
        ],
        // ... other question types
    ],
];