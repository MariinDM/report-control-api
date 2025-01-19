<?php

namespace App\Http\Controllers;

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

        if (!auth()->attempt($data)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token = Auth::user()->createToken(
            name: 'token',
            expiresAt: now()->addMinutes(30)
          );
      
          return response()
            ->json(
              data: [
                'message' => 'Login successful',
                'data' => [
                  'type' => 'Bearer',
                  'token' => $token->plainTextToken,
                  'expires_at' => $token->accessToken->expires_at->format('Y-m-d H:i:s'),
                ],
              ],
              status: 200
            );
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()
            ->json(
                data: [
                'message' => 'Unauthorized',
                'data' => null,
                ],
                status: 401
            );
        }

        Auth::user()->tokens()->delete();

        return response()
        ->json(
            data: [
            'message' => 'Logout successful',
            'data' => null,
            ],
            status: 200
        );
    }
}
