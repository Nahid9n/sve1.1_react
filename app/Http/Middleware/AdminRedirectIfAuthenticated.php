<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // dd($request->all());
        if (Auth::guard('admin')->check()) {
            // dd(Auth::guard('admin')->user());
            return redirect(RouteServiceProvider::ADMIN_HOME);
        }

        // dd('ok');
        return $next($request);
    }
}
