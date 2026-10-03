<?php

use App\Http\Middleware\CheckModuleAccess;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\PreventBackHistoryCache;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'module' => CheckModuleAccess::class,
            'admin' => EnsureIsAdmin::class,
        ]);

        // Empêche le navigateur de réafficher des pages authentifiées via le
        // bouton "Retour" après une déconnexion.
        $middleware->web(append: [
            PreventBackHistoryCache::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
