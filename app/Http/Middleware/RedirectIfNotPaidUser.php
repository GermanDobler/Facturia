<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotPaidUser
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login.user'); // Redirige al login del usuario común
        }

        $user = Auth::user();

        if (!in_array($user->role, ['user', 'admin'])) {
            abort(403, 'No tenés permisos para acceder a los artículos pagos.');
        }

        return $next($request);
    }
}
