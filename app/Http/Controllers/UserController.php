<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller {

    /**
     * Display a listing of the users
     *
     */
    public function index(Request $request) {
        $users = User::get();

        return view('users.index', compact('users'))->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create() {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'username' => 'required|unique:users',
            'password' => 'required',
            'roles' => 'required',
        ], [
            'name.required' => 'Name of the user is required',
            'email.unique' => 'The email is already registered on stre@mline',
            'username.required' => 'Enter username for the user',
            'password.required' => 'Enter password for the user',
            'roles.required' => 'Select some roles for the user'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
        ]);

        $this_user = User::find($user->id);
        $this_user->assignRole($request->input('roles'));

        flash("User created successfully")->success();
        return redirect()->route('user.index');
    }

    public function show($id) {
        $user = User::find($id);

        echo "Hello,";
        //return view('users.show', compact('user'));
    }

    public function edit($id) {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $user_roles = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'roles', 'user_roles'));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $id,
            'username' => 'required|unique:users,username,' . $id,
            'roles' => 'required',
        ], [
            'name.required' => 'Name of the user is required',
            'email.unique' => 'The email is already registered on stre@mline',
            'username.required' => 'Enter username for the user',
            'roles.required' => 'Select some roles for the user'
        ]);

        $user = User::find($id);

        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->username = $request->input('username');
        $user->save();

        $user->assignRole($request->input('roles'));

        flash("User updated successfully")->success();
        return redirect()->route('user.index');
    }

    public function destroy($id) {
        DB::table("users")->where('id', $id)->delete();

        flash("User has been deactivated.")->success();
        return redirect()->route('user.index');
    }
}
