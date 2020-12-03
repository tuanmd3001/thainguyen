<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUserCan
{

    public function handle($request, Closure $next, $permission, $guard = 'admins')
    {
        if (!Auth::guard($guard)->user()->can($permission)) {
            abort(403);
        }
        return $next($request);
    }
}
