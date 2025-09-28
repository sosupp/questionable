<?php
namespace Sosupp\Questionable\Services;

use Sosupp\Questionable\Models\Quiz;
use Sosupp\Questionable\Models\Question;
use Sosupp\SlimDashboard\Concerns\Filters\CommonFilters;
use Sosupp\SlimDashboard\Concerns\Filters\WithDateFormat;
use Sosupp\SlimDashboard\Contracts\Crudable;

class QuizService implements Crudable
{
    use WithDateFormat, CommonFilters;

    public function make(?int $id, array $data)
    {
        $quiz = $data['quizzable']->quizzes()->updateOrCreate(
            [
                'code' => $data['code'],
            ],
            [
                'year_id' => $data['year'],
                'version' => $data['version'] ?? null,
                'title' => $data['title'],
                'slug' => str($data['title'])->slug()->value(),
                'description' => $data['description'] ?? null,
                'time_limit' => $data['time_limit'] ?? null,
                'shuffle_questions' => $data['shuffle_questions'] ?? false,
                'shuffle_options' => $data['shuffle_options'] ?? false,
                'show_correct_answers' => $data['show_correct_answers'] ?? false,
                'starts_at' => $data['starts_at'] ?? null,
                'ends_at' => $data['ends_at'] ?? null,
            ]
        );

        return $quiz;
    }

    public function one(int|string $id) { }

    public function list(?int $limit = null, array $cols = ['*']) { }

    public function paginate(?int $limit = null, array $cols = ['*'])
    {
        return Quiz::query()
        ->where($this->statusCol, $this->status)
        ->when(!empty($this->searchTerm), function($q){
            $q->search($this->searchTerm);
        })
        ->with('questions')
        ->withCount('questions')
        ->dated($this->selectedDate)
        ->orderBy(column: $this->orderByColumn, direction: $this->orderByDirection)
        ->paginate(perPage: $limit, columns: $cols);
    }

    public function remove(int|string $id) { }

    public function createQuiz(array $data, $quizzable)
    {
        $quiz = $quizzable->quizzes()->create([

            'title' => $data['title'],
            'slug' => str($data['title'])->slug()->value(),
            'description' => $data['description'] ?? null,
            'time_limit' => $data['time_limit'] ?? null,
            'shuffle_questions' => $data['shuffle_questions'] ?? false,
            'shuffle_options' => $data['shuffle_options'] ?? false,
            'show_correct_answers' => $data['show_correct_answers'] ?? false,
            'starts_at' => $data['starts_at'] ?? null,
            'ends_at' => $data['ends_at'] ?? null,
        ]);

        if (isset($data['questions'])) {
            $this->attachQuestions($quiz, $data['questions']);
        }

        return $quiz;
    }

    public function attachQuestions(Quiz $quiz, array $questionIds)
    {
        $questions = Question::whereIn('id', $questionIds)->get();

        $order = 1;
        foreach ($questions as $question) {
            $quiz->questions()->attach($question->id, ['order' => $order++]);
        }

        return $quiz;
    }

}
