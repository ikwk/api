<?php

namespace App\Http\Middleware;

use Closure;

class JWTMiddleware
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
        $message = '';

        try {
            // cek token validation
            JWTAuth::parseToken()->authenticate();
            return $next($request);

        } catch (\Tymon\JWTAuth\Exception\TokenExpiredException $e) {
            //throw $th;
            $message = 'token expired';
        }
        catch (\Tymon\JWTAuth\Exception\TokenInvalidException $e) {
            //throw $th;
            $message = 'invalid token';
        }
        catch (\Tymon\JWTAuth\Exception\JWTException $e) {
            //throw $th;
            $message = 'provide token';
        }
        return response()->json([
            'success' => false,
            'message' => $message
        ]);
    }
}
