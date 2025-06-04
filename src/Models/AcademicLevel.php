<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicLevel extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'code', 'order'];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
