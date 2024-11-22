<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');
        if (! $token || ! Str::startsWith($token, 'Bearer ')) {
            return response()->json([
                'message' => 'Authorization token is required',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }

    public function redirectTo($request)
    {
        return $request->expectsJson()
            ? response()->json(['message' => 'Unauthenticated.'], Response::HTTP_UNAUTHORIZED)
            : redirect()->guest(route('login'));
    }
}
