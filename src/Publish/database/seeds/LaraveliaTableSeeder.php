<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LaraveliaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->comment("\n");
        $this->command->info('----------------------------------------------');
        $this->command->info('============== Create Permission =============');
        $this->command->info('----------------------------------------------');
        $this->command->comment("\n");
        $roles = config('laravelia.roles');
        $permissions = config('laravelia.permissions');
        $permissions_map = collect(config('laravelia.permissions_maps'));
        $user = config('laravelia.models.users')::first();
        foreach($roles as $key => $r){
        	$role = config('laravelia.models.roles')::create([
                'name' => $key,
                'display_name' => str_title($key),
                'description' => 'Role of ' . str_title($key)
            ]);
            $this->command->info(strtoupper($key) . ' Role created');
            $this->command->comment("\n");
            $array_permissions = [];
            foreach ($r['permissions'] as $module => $value){
                foreach (explode(',', $value) as $p => $perm){
                    $permissionValue = $permissions_map->get($perm);
                    $array_permissions[] = config('laravelia.models.permissions')::firstOrCreate([
                    	'index' => $module,
                        'name' => $permissionValue . '-' . $module,
                        'display_name' => str_title($permissionValue) . ' ' . str_title($module),
                        'description' => 'Permission of ' . str_title($permissionValue) . ' ' . str_title($module),
                    ])->id;
                    $this->command->info("Permission {$permissionValue} {$module} for {$module} has created.");
                }
            }
            $menus = config('laravelia.models.menu')::select('id as menu_id')->whereIn('en_name', $r['menu'])->get()->toArray();
            $role->menus()->sync($menus);
            $role->permissions()->sync($array_permissions);
            $this->command->comment("\n");
        }
        $user->attachRole(config('laravelia.models.roles')::first());
        // if (!empty($permissions)){
        // 	$permission_all = Permission::select('id as permission_id')->get()->toArray();
        //     $user->permissions()->sync($permission_all);
        // }
    }
}
