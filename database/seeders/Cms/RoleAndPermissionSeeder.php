<?php

namespace Database\Seeders\Cms;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'admin', 'user'];
        
        $permissions = ['view', 'create', 'update', 'destroy'];
        
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            
            if ($roleName === 'superadmin') {
                $role->givePermissionTo(Permission::all());
            }
            
            if ($roleName === 'admin') {
                $role->givePermissionTo(['view', 'create', 'update']);
            }
            
            if ($roleName === 'user') {
                $role->givePermissionTo(['view']);
            }
        }
    }
}
