<?php
namespace Sosupp\Questionable\Services;

use Sosupp\Questionable\Models\Question;
use Sosupp\SlimDashboard\Contracts\Crudable;

class QuestionService implements Crudable
{
    public function make(?int $id, array $data)
    {
        $question = Question::query()
        ->updateOrCreate(
            [
                'id' => $id,
            ],
            [
                'question_bank_id' => $data['bank'],
                'question_type_id' => $data['type'],
                'subject_id' => $data['subjectId'] ?? null,
                'academic_level_id' => $data['acadamicId'] ?? null,
                'year_id' => $data['year'] ?? null,
                'question_text' => $data['question'],
                'metadata' => $data['metaData'] ?? null,
                'points' => $data['points'] ?? 1,
                'is_active' => $data['status'] ?? true,
                'topic' => $data['topic'] ?? null,
                'difficulty_level' => $data['difficultLevel'] ?? null,
            ]
        );

        if(isset($data['options'])){
            $order = 1;
            foreach($data['options'] as $option){
                $question->options()->create([
                    'option_text' => $option['value'],
                    'is_correct' => $option['answer'] ?? false,
                    'metadata' => $option['meta'] ?? null,
                    'order' => $option['order'] ?? $order++,

                ]);
            }
        }

        // Add metadata if provided
        if (isset($data['meta'])) {
            foreach ($data['meta'] as $key => $value) {
                $question->meta()->create([
                    'key' => $key,
                    'value' => $value,
                ]);
            }
        }

        return $question;

    }

    public function makeMany(array $data)
    {
        $results = [];

        foreach($data as $question){
            $results[] = $this->make(
                id: null,
                data: $question,
            );
        }

        return $results;
    }

    public function one(int|string $id) { }

    public function list(?int $limit = null, array $cols = ['*']) { }

    public function paginate(?int $limit = null, array $cols = ['*']) { }

    public function remove(int|string $id) { }

}
