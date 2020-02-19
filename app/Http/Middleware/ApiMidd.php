<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Api;

class ApiMidd
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
        
        if(!$request->api_key) {
            return response()->json([
                    "statusCode" => 1,
                    "message" => "Your API Key was not supplied or is invalid",
                    "a"=>$request->api_key
                ], 401);
        }
        $check = Api::where('api_key', $request->api_key)->count();
        if (!$check) {
            return response()->json([
                    "statusCode" => 1,
                    "message" => "Your API Key was not supplied or is invalid",
                    "a"=>$request->api_key
                ], 401);
        }
        // return $response;
        return $next($request);
        // ->header('Access-Control-Allow-Origin', '*') 
        // ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
        // ->header('Access-Control-Allow-Headers', 'Content-type, X-Auth-Token, Origin, Authorization');
        
    }
}