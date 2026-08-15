<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class InjectUserToView
{
    public function handle($request, Closure $next)
    {
        View::share('user', Auth::user());
        return $next($request);
    }
}

