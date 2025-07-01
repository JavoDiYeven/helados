<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('/login');
        }

        $user = Auth::user();

        if (!$user->canAccessBackend()) {
            Auth::logout();
            return redirect()->route('/login')
                ->withErrors(['access' => 'No tienes permisos para acceder a esta área.']);
        }

        // Verificar si la cuenta está activa
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('/login')
                ->withErrors(['account' => 'Tu cuenta ha sido desactivada.']);
        }

        return $next($request);
    }
}
