<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $user = $token ? User::where('api_token', $token)->first() : null;

        abort_unless($user, 401, 'Unauthenticated.');

        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
