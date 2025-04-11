<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class TokenAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return redirect('/login');
        }

        $tokenString = substr($authHeader, 7);
        $token = PersonalAccessToken::findToken($tokenString);

        if (!$token || !$token->tokenable) {
            return redirect('/login');
        }

        $request->setUserResolver(function () use ($token) {
            $user = $token->tokenable;
            $user->withAccessToken($token);
            return $user;
        });

        return $next($request);
    }
}

