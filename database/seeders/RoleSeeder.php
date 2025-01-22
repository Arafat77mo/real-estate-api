<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $editorRole = Role::create(['name' => 'owner']);
        $userRole = Role::create(['name' => 'user']);

        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create permissions
        $permissionIds = Permission::pluck('id')->toArray(); // Get only the IDs of permissions

        // Assign the permissions by ID to the admin role
        $adminRole->givePermissionTo($permissionIds);


    }
}
