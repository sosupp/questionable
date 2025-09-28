<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Sosupp\Questionable\Traits\Quizzable;

class GlobalQuiz extends Model
{
    use HasFactory, Quizzable;

    protected $fillable = ['name'];


}
