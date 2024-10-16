<?php

namespace App\Http\Middleware;

use Closure;
// use Namshi\JOSE\JWT;
// use PHPOpenSourceSaver\JWTAuth\JWT;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {


        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => 'Token is Invalid'], 401);
            } else if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => 'Token is Expired'], 401);
            } else {
                return response()->json(['status' => 'Authorization Token not found'], 401);
            }
        }
        return $next($request);


        // return $next($request);
        // Check for a token in the Authorization header
    //     $token = $request->header('Authorization');

    //     if (!$token) {
    //         return response()->json(['error' => 'Authorization token not found'], 401);
    //     }

    //     try {
    //         $user = JWTAuth::parseToken()->authenticate();
    //     } catch (Exception $e) {
    //         if ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException) {
    //             return response()->json(['error' => 'Token has expired'], 401);
    //         } elseif ($e instanceof \PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException) {
    //             return response()->json(['error' => 'Token is invalid'], 401);
    //         } else {
    //             return response()->json(['error' => 'Authorization token not found'], 401);
    //         }
    //     }

    //     return $next($request);
    
    }
}