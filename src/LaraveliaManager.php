<?php

namespace Ken\Laravelia;

use Illuminate\Foundation\Application;

class LaraveliaManager implements Contracts\Config
{
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * Get of config.
     *
     * @return \Ken\Laravelia\Config
     */
    public function getRoles()
    {
        return $this->app['config']['laravelia.roles'];
    }

    /**
     * Get of config.
     *
     * @return \Ken\Laravelia\Config
     */
    public function getPermissions()
    {
        return $this->app['config']['laravelia.permissions'];
    }
    
    /**
     * Get of config.
     *
     * @return \Ken\Laravelia\Config
     */
    public function getPermissionMaps()
    {
        return $this->app['config']['laravelia.permissions_maps'];
    }
}
