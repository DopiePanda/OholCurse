<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DebugBarMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if(!\Auth::check() || \Auth::user()->id !== 1 || env('DEBUGBAR_ENABLED') !== 'true') {
            \Debugbar::disable();
        }
        return $next($request);
    }
}
