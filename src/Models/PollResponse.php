<?php
namespace Sosupp\Questionable\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PollResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'poll_id',
        'question_id',
        'user_id',
        'option_id',
        'answer_text'
    ];

    public function poll()
    {
        return $this->belongsTo(Poll::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

    /**
     * Get the response text based on the type of response
     */
    public function getResponseTextAttribute()
    {
        if ($this->option_id) {
            return $this->option->option_text;
        }
        
        return $this->answer_text;
    }

    /**
     * Scope for anonymous responses (where user_id is null)
     */
    public function scopeAnonymous($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope for responses from a specific user
     */
    public function scopeFromUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}