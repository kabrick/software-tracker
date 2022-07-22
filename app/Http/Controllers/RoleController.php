<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller {

    function __construct() {
        $this->middleware('permission:role_list');
        $this->middleware('permission:role_create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role_edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role_delete', ['only' => ['destroy']]);
    }

    public function index(Request $request) {
        $roles = Role::withCount('users')->withCount('permissions')->orderBy('name')->get();

        return view('roles.index', compact('roles'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create() {
        $permissions = Permission::get();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ], [
            'name.required' => 'Name of the role is required',
            'name.unique' => 'The role name is already registered on stre@mline',
            'permission.required' => 'Select some permissions for the role'
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permission'));

        flash("Role created successfully")->success();
        return redirect()->route('roles.index');
    }

    public function show($id) {
        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id) {
        $role = Role::find($id);
        $permissions = Permission::get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_id", $id)->pluck('permission_id', 'permission_id')->all();

        return view('roles.edit', compact('role', 'rolePermissions', 'permissions'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        flash("Role updated successfully")->success();
        return redirect()->route('roles.index');
    }

    public function destroy($id) {
        DB::table("roles")->where('id', $id)->delete();

        flash("Role has been deactivated")->success();
        return redirect()->route('roles.index');
    }
}
