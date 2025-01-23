<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class Authentication extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $data['email'])
        ->where('active', 1)
        ->first();

        if (!$user) {
            return response()->json([
              'message' => 'Usuario sin autorización',
              'data'=> null
            ], 400);
        }

        if (!auth()->attempt($data)) {
            return response()->json([
              'message' => 'Credenciales Incorrectas',
              'data'=> null
            ], 400);
        }

        $user = Auth::user();

        $token = Auth::user()->createToken(
            name: 'token',
            expiresAt: now()->addMinutes(30)
          );
      
          return response()
            ->json(
               [
                'message' => 'Inicio de sesión exitoso',
                'data' => [
                  'type' => 'Bearer',
                  'token' => $token->plainTextToken,
                  'expires_at' => $token->accessToken->expires_at->format('Y-m-d H:i:s'),
                ],
                'user' => $user,
              ],
               200
            );
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()
            ->json(
                 [
                'message' => 'Sin autorización',
                'data' => null,
                ],
                 401
            );
        }

        Auth::user()->tokens()->delete();

        return response()
        ->json(
             [
            'message' => 'Sesión cerrada',
            'data' => null,
            ],
             200
        );
    }
}
