<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            ["name" => "projects_list", "description" => "View Projects", "guard_name" => "web"],
            ["name" => "projects_create", "description" => "Create Projects", "guard_name" => "web"],
            ["name" => "projects_edit", "description" => "Edit Projects", "guard_name" => "web"],
            ["name" => "projects_delete", "description" => "Delete projects", "guard_name" => "web"],
            ["name" => "role_list", "description" => "View Roles", "guard_name" => "web"],
            ["name" => "role_create", "description" => "Create Role", "guard_name" => "web"],
            ["name" => "role_edit", "description" => "Edit Role", "guard_name" => "web"],
            ["name" => "role_delete", "description" => "Delete Role", "guard_name" => "web"],
            ["name" => "users_list", "description" => "View Users", "guard_name" => "web"],
            ["name" => "users_create", "description" => "Create User", "guard_name" => "web"],
            ["name" => "users_edit", "description" => "Edit User", "guard_name" => "web"],
            ["name" => "users_delete", "description" => "Delete User", "guard_name" => "web"],
            ["name" => "view_archived_projects", "description" => "View Archived Projects", "guard_name" => "web"],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name'],
                'description' => $permission['description'],
                'guard_name' => $permission['guard_name']
            ]);
        }
    }
}
