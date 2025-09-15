<?php

// app/Http/Middleware/CanViewPaidArticles.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CanViewPaidArticles
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user && in_array($user->role, ['user', 'admin'])) {
            return $next($request);
        }

        abort(403, 'No tenés permisos para ver artículos pagos.');
    }
}
