<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class AuthMiddleware
{
    public function handle($request, Closure $next)
    {
        if (!Session::has('user')) {
            return redirect('/'); // redirect to login if not authenticated
        }

        return $next($request); // continue to requested route
    }
}