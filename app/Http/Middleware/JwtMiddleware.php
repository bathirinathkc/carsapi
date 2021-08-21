<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
// use Tymon\JWTAuth;

class JwtMiddleware extends BaseMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['status' => false , 'code' => 400 , 'message' => 'Token is Invalid', 'data' => array('*')]);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['status' => false , 'code' => 400 , 'message' => 'Token is Expired', 'data' => array('*')]);
            } else {
                return response()->json(['status' => false , 'code' => 400 , 'message' => 'Authorization Token not found', 'data' => array('*')]);
            }
        }
        return $next($request);
    }
}
