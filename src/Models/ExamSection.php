<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'title',
        'slug',
        'description',
        'time_limit',
        'order',
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class)
            ->withPivot('order')
            ->orderBy('order');
    }
}
