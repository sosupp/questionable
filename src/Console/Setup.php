<?php
namespace Sosupp\Questionable\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Sosupp\Questionable\Models\QuestionBank;
use Sosupp\Questionable\Models\QuestionType;

use function Livewire\str;

class Setup extends Command
{
    protected $signature = 'questionable:setup';
    protected $description = 'Perform default and necessary setup for the package when installed in a project';


    public function handle()
    {
        // publish migration, assets, views, config
        $this->call('vendor:publish --tag=questionable');
        
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