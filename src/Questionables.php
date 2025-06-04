<?php
namespace Sosupp\Questionable;

use Sosupp\Questionable\Services\PollService;
use Sosupp\Questionable\Services\QuizService;
use Sosupp\Questionable\Services\QuestionBankService;

class Questionables
{
    protected $questionBankService;
    protected $quizService;
    protected $pollService;

    public function __construct(
        QuestionBankService $questionBankService,
        QuizService $quizService,
        PollService $pollService
    ) {
        $this->questionBankService = $questionBankService;
        $this->quizService = $quizService;
        $this->pollService = $pollService;
    }

    public function createQuestionBank(array $data, $owner)
    {
        return $this->questionBankService->createQuestionBank($data, $owner);
    }

    public function createQuiz(array $data, $quizzable)
    {
        return $this->quizService->createQuiz($data, $quizzable);
    }

    public function createPoll(array $data, $pollable)
    {
        return $this->pollService->createPoll($data, $pollable);
    }

    
}
