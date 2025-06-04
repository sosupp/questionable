<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuestionBank extends Model
{
    use HasFactory, HasQuestions;

    protected $fillable = ['name', 'slug', 'description', 'owner_id', 'owner_type'];

    public function owner()
    {
        return $this->morphTo();
    }

}
