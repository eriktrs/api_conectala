<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        try {
            // Check if the token is present in the request
            // If the token is valid, get the authenticated user
            $user = JWTAuth::parseToken()->authenticate();

            // If the user is not found, return an error response
            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            // If the token is expired, return an error response
            return response()->json(['error' => 'Token expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            // If the token is invalid, return an error response
            return response()->json(['error' => 'Token invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {

            // If the token is absent, return an error response
            return response()->json(['error' => 'Token absent'], 401);
        } catch (\Illuminate\Auth\AuthenticationException $e) {

            // If the user is not authenticated, return an error response
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        return $next($request);
    }
}
