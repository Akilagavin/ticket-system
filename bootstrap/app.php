<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Registering Custom Middleware Alias
        $middleware->alias([
            'ticket.status' => \App\Http\Middleware\CheckTicketStatus::class,
        ]);

        // 2. Configure Guest Redirection
        // This is the "Gatekeeper" instruction: 
        // If a guest hits an 'auth' route, send them to /login
        $middleware->redirectGuestsTo('/login'); 
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();