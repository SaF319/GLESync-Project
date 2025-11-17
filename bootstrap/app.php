<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Validación CSRF
        $middleware->validateCsrfTokens(except: []);

        // Alias de middleware root
        $middleware->alias([
            'root' => \App\Http\Middleware\RootMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Aquí puedes personalizar el manejo de excepciones si lo deseas
    })
    ->create();
