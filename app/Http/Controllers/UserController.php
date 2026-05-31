<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //index
    public function index(Request $request)
    {
        //get all users with pagination
        $users = DB::table('users')
        ->when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%')
                ->orWhere('email', 'like', '%' . $name . '%');
        })

        //filter by role
        ->when($request->input('role'), function ($query, $role) {
            return $query->where('role', $role);
        })
        
        ->orderByRaw("
            CASE
            WHEN role = 'admin' THEN 1
            WHEN role = 'staff' THEN 2
            WHEN role = 'user' THEN 3
            ELSE 4
            END
        ")
        ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    //create
    public function create()
    {
        return view('pages.users.create');
    }

    //store
    public function store(Request $request)
    {
        //validate
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,user,staff',
        ]);
        //create user
        $user = new \App\Models\User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = $request->role;
        $user->save();
        //redirect
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    //edit
    public function edit($id)
    {
        $user = \App\Models\User::find($id);
        return view('pages.users.edit', compact('user'));
    }

    //update
    public function update(Request $request, $id)
    {
        //validate
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);
        //update user
        $user = \App\Models\User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->role = $request->role;
        $user->save();
        //redirect
        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    //delete
    public function destroy($id)
    {        $user = \App\Models\User::find($id);
        $user->delete();
        //redirect
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    //show
    public function show($id)
    {        $user = \App\Models\User::find($id);
        return view('users.show', compact('user'));
    }

}
