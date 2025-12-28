<?php
namespace Sosupp\Questionable\Console;

use Illuminate\Console\Command;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\QuestionType;

class Install extends Command
{
    protected $signature = 'questionable:install';
    protected $description = 'Run after setup command - in order to handle any DB migration run and default data or seeders';


    public function handle()
    {
        // publish migration, assets, views, config
        $this->call('migrate');
        
        // seed DB for question types
        // seed DB for default question bank
        $this->defaultSeeds();

    }

    protected function defaultSeeds()
    {
        $this->info('Adding default question types:');

        $types = [
            'multiple choice',
            'true/false',
            'short answer',
            'rating scale',
            'ranking',
            'matching',
        ];

        foreach ($types as $type) {
            QuestionType::query()
            ->updateOrCreate(
                [
                    'name' => $type
                ],
                [
                    'slug' => str($type)->slug(),

                ]
            );
        }

        $this->info('Adding default question bank name');
        QuestionBank::query()
        ->updateOrCreate(
            [
                'name' => 'general',
            ],
            [
                'slug' => 'general',
                'description' => 'general knowledge questions',
            ]
        );

    }

    
}