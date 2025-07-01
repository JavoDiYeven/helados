<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (! $request->user()) {
            return redirect()->route('backend.login');
        }

        return $next($request);
    }
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('backend.login');
    }
}
