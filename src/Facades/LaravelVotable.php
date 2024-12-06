<?php

namespace Zeroday\LaravelVotable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Zeroday\LaravelVotable\LaravelVotable
 */
class LaravelVotable extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Zeroday\LaravelVotable\LaravelVotable::class;
    }
}
