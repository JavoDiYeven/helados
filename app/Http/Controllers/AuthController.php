<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;
use App\Http\Requests\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Llamada manual al método authenticated()
            return $this->authenticated($request, Auth::user());
        }

        return back()->withErrors([
            'email' => 'Credenciales inválidas.',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        // Log del login exitoso
        logger()->info('Usuario autenticado', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            
        ]);

        return redirect()->intended('/backend/dashboard');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Log del logout
        logger()->info('Usuario cerró sesión', [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('message', 'Sesión cerrada correctamente.');
    }

    protected function checkTooManyFailedAttempts(Request $request)
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = RateLimiter::availableIn($this->throttleKey($request));
            
            throw ValidationException::withMessages([
                'email' => ["Demasiados intentos fallidos. Intenta de nuevo en {$seconds} segundos."],
            ]);
        }
    }

    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(
            Str::lower($request->input('email')).'|'.$request->ip()
        );
    }
}
