<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Auth;
use Closure;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::user() && Auth::user()->id == 1) {
            return $next($request);
        }

        return redirect()->route('search');
    }
}