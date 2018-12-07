<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Admin
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
        if (!isset(Auth::user()->Role))
            return redirect('/404');
            
        if (Auth::user()->Role === "admin")
            return $next($request);
        else return redirect('/default?msg=Only admins are allowed in this area');
    }
}
