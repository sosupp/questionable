<?php
namespace Sosupp\Questionable\Traits;

use Sosupp\Questionable\Models\Poll;

trait Pollable
{
    public function polls()
    {
        return $this->morphMany(Poll::class, 'pollable');
    }

    public function createPoll(array $attributes)
    {
        return $this->polls()->create($attributes);
    }
}