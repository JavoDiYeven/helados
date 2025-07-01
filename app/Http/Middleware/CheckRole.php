<?php

use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Auth;

class CheckRole extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/login')->withErrors(['access' => 'No tienes permisos para acceder a esta área.']);
        }

        return $next($request);
    }
}