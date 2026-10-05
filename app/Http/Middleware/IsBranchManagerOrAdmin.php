<?php

namespace App\Http\Middleware;

use Closure;

class IsBranchManagerOrAdmin
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
        if(!auth()->user()->is_admin && !auth()->user()->is_manager) abort(403, env('ERROR_403'));

        return $next($request);
    }
}
