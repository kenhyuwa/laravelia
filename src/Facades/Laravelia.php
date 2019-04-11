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

    /**
     * Register the typical laravelia routes for an application.
     *
     * @param  array  $options
     * @return void
     */
    public static function routes(array $options = [])
    {
        config('laravelia.models.menu')::routes();
        config('laravelia.models.roles')::routes();
        config('laravelia.models.permissions')::routes();
    }
}
