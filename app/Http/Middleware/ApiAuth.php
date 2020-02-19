<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;

class ApiAuth
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
        // $response = $next($request);
        if(!$request->header('Authorization')) {
            return response()->json([
                    "statusCode" => 1,
                    "message" => "401 Unauthorized",
                ], 401);
        }
        $token = $request->header('Authorization');
        $token = str_replace('bearer ', '', $token);
        $token = str_replace('bearer', '', $token);
        $token = str_replace(' bearer ', '', $token);
        
        $check = User::where('token', $token)->count();
        if (!$check) {
            return response()->json([
                    "statusCode" => 1,
                    "message" => "401 Unauthorized",
                ], 401);
        }
        // return $response;
        return $next($request);
        // ->header('Access-Control-Allow-Origin', '*') 
        // ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        // ->header('Access-Control-Allow-Headers', 'Content-type, X-Auth-Token, Origin, Authorization');
        
    }
}