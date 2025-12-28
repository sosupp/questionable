<?php
namespace Sosupp\Questionable\Console;

use Illuminate\Console\Command;

class Setup extends Command
{
    protected $signature = 'questionable:setup';
    protected $description = 'Perform default and necessary setup for the package when installed in a project';


    public function handle()
    {
        // publish migration, assets, views, config
        $this->call('vendor:publish', [
            '--tag' => 'questionable'
        ]);

    }


}
