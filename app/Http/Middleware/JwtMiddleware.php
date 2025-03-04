<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Tymon\JWTAuth\Facades\JWTAuth;
// use Tymon\JWTAuth\Exceptions\TokenExpiredException;
// use Tymon\JWTAuth\Exceptions\JWTException;
// use Illuminate\Support\Facades\Cookie;

// class JwtMiddleware
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next): Response
//     {
//         $validToken = false;
//         try {
//             $validToken = $request->cookie('token');
//             $user = JWTAuth::setToken($validToken)->authenticate();

//             if (JWTAuth::getPayload($validToken)->get('exp') < time() + 60*15) {
//                 $validToken = JWTAuth::refresh($validToken);
//                 JWTAuth::setToken($validToken);
//                 $request->cookie(Cookie::make('token', $validToken, 60*24, '/', null, false, true));
//             }

//         } catch (TokenExpiredException $e) {
//             try {
//                 $validToken = JWTAuth::refresh($request->cookie('token'));
//                 JWTAuth::setToken($validToken);
//                 $request->cookie(Cookie::make('token', $validToken, 60*24, '/', null, false, true));
//                 error_log($validToken);
//             } catch (JWTException $e) {
//                 return response()->json(['error' => 'Token cannot be refreshed, please log in again.'], 401);
//             }

//         } catch (JWTException $e) {
//             return response()->json(['error' => 'Unauthorized'], 401);
//         }

//         // Continue with the request
//         // $response = $next($request);

//         // If a new token was generated, add it to the response headers
//         // if (isset($validToken)) {
//         //     $response->headers->set('Authorization', 'Bearer ' . $validToken);
//         // }
        
//         return $next($request);
//     }
// }


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Cookie;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
    
            if (!$user) {
                return response()->json(null, 401);
            }

        } catch (TokenExpiredException $e) {

            try {

                $newToken = JWTAuth::refresh();

                $user = JWTAuth::setToken($newToken)->authenticate();

            } catch (JWTException $e) {
                return response()->json(null, 401);
            }
        } catch (JWTException $e) {
            return response()->json(null, 401);
        }

        $response = $next($request);

        if (isset($newToken)) {
            $response->headers->set('Access-Control-Expose-Headers', 'Authorization');
            $response->headers->set('Authorization', "Bearer $newToken");
        }

        return $response;
    }
}
