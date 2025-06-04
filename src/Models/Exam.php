<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'total_time',
        'passing_score',
        'max_attempts',
        'shuffle_sections',
        'shuffle_questions',
        'shuffle_options',
        'require_proctoring',
        'show_score_after',
        'show_answers_after',
        'available_from',
        'available_to',
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'available_to' => 'datetime',
    ];

    public function attempts()
    {
        return $this->hasMany(ExamAttempt::class);
    }

    public function isAvailable()
    {
        $now = now();
        
        if ($this->available_from && $this->available_from->gt($now)) {
            return false;
        }
        
        if ($this->available_to && $this->available_to->lt($now)) {
            return false;
        }
        
        return true;
    }
}
