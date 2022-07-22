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
            ["name" => "project_versions_list", "description" => "View Project Versions", "guard_name" => "web"],
            ["name" => "project_versions_create", "description" => "Create Project Versions", "guard_name" => "web"],
            ["name" => "project_versions_edit", "description" => "Edit Project Versions", "guard_name" => "web"],
            ["name" => "project_versions_delete", "description" => "Delete/Archive Project Versions", "guard_name" => "web"],
            ["name" => "project_versions_clone", "description" => "Clone Project Versions", "guard_name" => "web"],
            ["name" => "project_versions_print", "description" => "Generate Project Versions PDF", "guard_name" => "web"],
            ["name" => "project_versions_print_order", "description" => "Change Project Versions Print Order", "guard_name" => "web"],
            ["name" => "project_modules_list", "description" => "View Project Version Modules", "guard_name" => "web"],
            ["name" => "project_features_list", "description" => "View Project Version Features", "guard_name" => "web"],
            ["name" => "project_features_archive", "description" => "Delete/Archive Project Version Features", "guard_name" => "web"],
            ["name" => "project_features_edit", "description" => "Edit Project Version Features", "guard_name" => "web"],
            ["name" => "project_features_publish", "description" => "Publish and Un-Publish Project Version Features", "guard_name" => "web"],
            ["name" => "project_features_generate_pdf", "description" => "Generate Project Version Features PDF", "guard_name" => "web"],
            ["name" => "project_modules_create", "description" => "Create Project Version Modules", "guard_name" => "web"],
            ["name" => "project_modules_edit", "description" => "Edit Project Version Modules", "guard_name" => "web"],
            ["name" => "project_modules_delete", "description" => "Delete/Archive Project Version Modules", "guard_name" => "web"],
            ["name" => "project_features_create", "description" => "Create Project Version Features", "guard_name" => "web"],
            ["name" => "project_modules_print", "description" => "Generate Project Version Modules PDF", "guard_name" => "web"],
        ];

        foreach ($permissions as $permission) {
            try {
                Permission::firstOrCreate([
                    'name' => $permission['name'],
                    'description' => $permission['description'],
                    'guard_name' => $permission['guard_name']
                ]);
            } catch (\Exception $exception) {
                //
            }
        }
    }
}
