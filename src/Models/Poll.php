<?php

namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Sosupp\SlimDashboard\Concerns\Filters\CommonScopes;

class Poll extends Model
{
    use HasFactory, SoftDeletes, CommonScopes;

    protected $fillable = [
        'year_id',
        'category_id',
        'title',
        'slug',
        'description',
        'is_anonymous',
        'is_active',
        'starts_at',
        'ends_at',
        'views',
    ];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'poll_questions')
                    ->withPivot('order')
                    ->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(PollResponse::class);
    }

    public function isAvailable()
    {
        $now = now();
        
        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }
        
        if ($this->ends_at && $this->ends_at->lt($now)) {
            return false;
        }
        
        return $this->is_active;
    }

    public function responseCount()
    {
        return $this->is_anonymous
            ? $this->responses()->count()
            : $this->responses()->distinct('user_id')->count('user_id');
    }
}
