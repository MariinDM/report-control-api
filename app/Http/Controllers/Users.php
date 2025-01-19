<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Users extends Controller
{
    public function index(Request $request)     
    {
        $users = User::all();
        return response()->json($users);
    }

    public function create(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->save();
        return response()->json(
            data:[
                'message' => 'User created successfully',
                'data' => null,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->save();
        return response()->json(
            data:[
                'message' => 'User updated successfully',
                'data' => null,
        ]);
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->active = !$user->active;
        $user->save();
        return response()->json(
            data:[
                'message' => 'User deleted successfully',
                'data' => null,
        ]);
    }
}
