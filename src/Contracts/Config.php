<?php 

namespace Ken\Laravelia\Contracts;

interface Config 
{
	/**
     * Get an Menu implementation.
     *
     * @param  array  $menu
     * @return \Ken\Laravelia\Contracts\Config
     */
    public function getRoles();

    /**
     * Get an Menu implementation.
     *
     * @param  array  $permissions
     * @return \Ken\Laravelia\Contracts\Config
     */
    public function getPermissions();

    /**
     * Get an Menu implementation.
     *
     * @param  array  $permission_maps
     * @return \Ken\Laravelia\Contracts\Config
     */
    public function getPermissionMaps();
}