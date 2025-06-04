<?php
namespace Sosupp\Questionable\Providers;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Sosupp\Questionable\Http\Livewire\QuestionBankManager;

class QuestionableServiceProdvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'questionable');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        
        Livewire::component('questionable::question-bank-manager', QuestionBankManager::class);
        Livewire::component('questionable::quiz-manager', QuizManager::class);
        Livewire::component('questionable::poll-manager', PollManager::class);
        
        $this->publishes([
            __DIR__.'/../../config/questionable.php' => config_path('questionable.php'),
            __DIR__.'/../../resources/views' => resource_path('views/vendor/questionable'),
            __DIR__.'/../../resources/assets' => public_path('vendor/questionable'),
        ], 'questionable');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/questionable.php', 'questionable'
        );
        
        $this->app->bind('questionable', function() {
            return new Questionables;
        });
    }

}