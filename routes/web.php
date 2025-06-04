<?php

use Illuminate\Support\Facades\Route;

// Quiz Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/quizzes', function () {
        return view('quizzes.index');
    })->name('questionable::quiz.list');
    
    Route::get('/quiz/{quizId}', function ($quizId) {
        return view('quizzes.take', ['quizId' => $quizId]);
    })->name('questionable::quiz.take');
    
    Route::get('/quiz/results/{attemptId}', function ($attemptId) {
        return view('quizzes.results', ['attemptId' => $attemptId]);
    })->name('questionable::quiz.results');
});