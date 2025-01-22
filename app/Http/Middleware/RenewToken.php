<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Laravel\Sanctum\PersonalAccessToken;

class RenewToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->bearerToken()) {
            $token = PersonalAccessToken::findToken($request->bearerToken());
            if ($token) {
                $token->forceFill(['expires_at' => now()->addMinutes(30)])->save();
            }
        }

        return $next($request);
    }
}
