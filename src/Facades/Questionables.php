<?php
namespace Sosupp\Questionable\Facades;

use Illuminate\Support\Facades\Facade;

class Questionables extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'questionables';
    }
}