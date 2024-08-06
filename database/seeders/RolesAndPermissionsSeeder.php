<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
  
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::create(['name' => 'create documents']);
        Permission::create(['name' => 'view documents']);
        Permission::create(['name' => 'assign tasks']);
        Permission::create(['name' => 'view tasks']);

        // Create roles and assign existing permissions
        $role = Role::create(['name' => 'creator']);
        $role->givePermissionTo(['create documents', 'assign tasks', 'view documents']);

        $role = Role::create(['name' => 'organization']);
        $role->givePermissionTo(['view tasks']);
    }
}
