<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exam_id',
        'started_at',
        'completed_at',
        'score',
        'total_score',
        'passed',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function sections()
    {
        return $this->hasMany(ExamAttemptSection::class);
    }

    public function calculateResults()
    {
        $score = 0;
        $totalScore = 0;
        
        foreach ($this->sections as $section) {
            foreach ($section->responses as $response) {
                if ($response->is_correct) {
                    $score += $response->points_earned;
                }
                $totalScore += $response->question->points;
            }
        }
        
        $this->update([
            'score' => $score,
            'total_score' => $totalScore,
            'passed' => $this->exam->passing_score 
                ? $score >= $this->exam->passing_score 
                : null,
            'completed_at' => now(),
        ]);
        
        return $this;
    }

}
