<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\User;

class Push
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
        $response = $next($request);
        
        if (!session('is_login') || session('user')['roles'] != 3) {
            return redirect(route('push.login'));
        }
        return $response;
    }
}