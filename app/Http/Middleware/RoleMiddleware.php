<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {   
        if ($request->user()->role_id !== 1) {
            return response()->json(
                data:[
                    'message' => 'Unauthorized',
                    'data' => null,
            ], status: 401);
        }
        return $next($request);
    }
}
