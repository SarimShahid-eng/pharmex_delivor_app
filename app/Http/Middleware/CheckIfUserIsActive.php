<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(auth()->check() && !@auth()->user()->is_active){
            Auth::logout();
            return redirect(route('login'))->withErrors([
                'active' => 'You account has been deactivated by administrator. Please contact administrator for further details.'
            ]);
        }
        return $next($request);
    }
}
