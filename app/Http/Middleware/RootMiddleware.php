<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RootMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si no está logueado → redirigir al login
        if (!$user) {
            return redirect()->route('login')
                ->withErrors(['auth' => 'Debes iniciar sesión.']);
        }

        // Si no es root → abortar
        if (!$user->es_root) {
            abort(403, 'Acceso denegado. Solo root.');
        }

        return $next($request);
    }
}
