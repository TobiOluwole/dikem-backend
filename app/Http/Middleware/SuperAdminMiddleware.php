<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class SuperAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user->role !== 'superadmin') {
                return response()->json(['error' => 'Doesn\'t exist for you'], 404);
            }
            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Doesn\'t exist for you'], 404);
        }
    }
}