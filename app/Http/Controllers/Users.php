<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class Users extends Controller
{
    public function index(Request $request)     
    {
        $users = User::with('role')->get();
        return response()->json([
            'message' => 'Usuarios Obtenidos',
            'data' => $users,
        ],200);
    }

    public function create(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->save();
        return response()->json([
            'message' => 'Usuario Creado',
            'data' => null,
        ],200);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'role_id' => 'required',
        ]);

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado',
                'data' => null,
            ],400);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role_id = $request->role_id;
        $user->save();
        return response()->json([
            'message' => 'Usuario Actualizado',
            'data' => null,
        ],200);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado',
                'data' => null,
            ],400);
        }

        $user->active = !$user->active;
        $user->save();
        return response()->json([
            'message' => 'Estado cambiado',
            'data' => null,
        ],200);
    }
}
