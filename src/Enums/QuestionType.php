<?php
namespace Sosupp\Questionable\Enums;

enum QuestionType: int
{
    case MULTIPLE_CHOICE = 1;
    case TRUE_FALSE = 2;
    case SHORT_ANSWER = 3;
    case RATING_SCALE = 4;
    case RANKING = 5;
    case MATCHING = 6;

    public function label(): string
    {
        return match($this) {
            self::MULTIPLE_CHOICE => 'Multiple Choice',
            self::TRUE_FALSE => 'True/False',
            self::SHORT_ANSWER => 'Short Answer',
            self::RATING_SCALE => 'Rating Scale',
            self::RANKING => 'Ranking',
            self::MATCHING => 'Matching',
        };
    }
    
    public function isGradable(): bool
    {
        return match($this) {
            self::MULTIPLE_CHOICE, self::TRUE_FALSE => true,
            default => false,
        };
    }

    
}