<?php

namespace Ken\Laravelia\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Laravel\Laravelia\LaraveliaManager
 */
class Laravelia extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravelia';
    }
}
