<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $admin = Role::create(['name' => 'Admin']);
        $partner = Role::create(['name' => 'Partner']);
        $subUser = Role::create(['name' => 'Sub-User']);

        // Create permissions
        $permissions = [
            'manage users',
            'view reports',
            'create projects',
            'edit projects',
            'delete projects',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign permissions to roles
        $admin->givePermissionTo(Permission::all());
        $partner->givePermissionTo(['view reports', 'create projects']);
        $subUser->givePermissionTo(['view reports']);
    }
}
