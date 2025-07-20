<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Pest\Plugins\Parallel\Handlers\Pest;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'read users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'create roles']);
        Permission::create(['name' => 'read roles']);
        Permission::create(['name' => 'update roles']);
        Permission::create(['name' => 'delete roles']);

        // Permission::create(['name' => 'create permissions']);
        // Permission::create(['name' => 'read permissions']);
        // Permission::create(['name' => 'update permissions']);
        // Permission::create(['name' => 'delete permissions']);

        Permission::create(['name' => 'create documents']);
        Permission::create(['name' => 'read documents']);
        Permission::create(['name' => 'update documents']);
        Permission::create(['name' => 'delete documents']);
        Permission::create(['name' => 'restore documents']);
        Permission::create(['name' => 'force delete documents']);

        Permission::create(['name' => 'update approval']);
        Permission::create(['name' => 'update rejection']);

        Permission::create(['name' => 'create revisions']);

        Role::create(['name' => 'admin'])->givePermissionTo(Permission::all());
    }
}
