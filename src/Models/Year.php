<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Year extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'start_year', 'end_year', 'is_current',
        'label'
    ];

    protected $casts = [
        'is_current' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    
    public static function current()
    {
        return static::where('is_current', true)->first();
    }
    

}
