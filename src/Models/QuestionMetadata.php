<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionMetadata extends Model
{
    use HasFactory;

    protected $fillable = ['question_id', 'key', 'value'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
