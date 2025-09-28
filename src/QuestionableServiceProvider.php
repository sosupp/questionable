<?php
namespace Sosupp\Questionable;

use Livewire\Livewire;
use Illuminate\Support\ServiceProvider;
use Sosupp\Questionable\Facades\Questionables;
use Sosupp\Questionable\Livewire\Exams\ExamTaker;
use Sosupp\Questionable\Livewire\Polls\PollTaker;
use Sosupp\Questionable\Livewire\Quizzes\QuizList;
use Sosupp\Questionable\Livewire\Exams\ExamManager;
use Sosupp\Questionable\Livewire\Polls\PollManager;
use Sosupp\Questionable\Livewire\Quizzes\QuizTaker;
use Illuminate\Database\Eloquent\Relations\Relation;
use Sosupp\Questionable\Livewire\QuestionBankManager;
use Sosupp\Questionable\Livewire\Quizzes\QuizManager;
use Sosupp\Questionable\Models\GlobalQuiz;

class QuestionableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'questionable');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        Livewire::component('questionable::question-bank-manager', QuestionBankManager::class);
        Livewire::component('questionable::quiz-manager', QuizManager::class);
        Livewire::component('questionable::quiz-taker', QuizTaker::class);
        Livewire::component('questionable::quiz-list', QuizList::class);
        Livewire::component('questionable::poll-manager', PollManager::class);
        Livewire::component('questionable::poll-taker', PollTaker::class);
        // Livewire::component('questionable::poll-list', PollList::class);
        Livewire::component('questionable::exam-manager', ExamManager::class);
        Livewire::component('questionable::exam-taker', ExamTaker::class);
        
        $this->publishes([
            __DIR__.'/../config/questionable.php' => config_path('questionable.php'),
            __DIR__.'/../resources/views' => resource_path('views/vendor/questionable'),
            __DIR__.'/../resources/assets' => public_path('vendor/questionable'),
        ], 'questionable');

        Relation::morphMap([
            'global' => GlobalQuiz::class,
        ]);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/questionable.php', 'questionable'
        );
        
        $this->app->bind('questionable', function() {
            return new Questionables;
        });
    }

}