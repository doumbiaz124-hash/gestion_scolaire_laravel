<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasModule($module)) {
            abort(403, "Vous n'avez pas accès à ce tableau de bord.");
        }

        return $next($request);
    }
}
