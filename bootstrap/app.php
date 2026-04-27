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
        /**
         * 1. Registering Custom Middleware Alias
         * This allows you to use 'ticket.status' in your routes/web.php
         * instead of the full class path.
         */
        $middleware->alias([
            'ticket.status' => \App\Http\Middleware\CheckTicketStatus::class,
        ]);

        /**
         * 2. Global Web Middleware (Optional)
         * If you wanted to apply something to EVERY web route, you would use:
         * $middleware->web(append: [ ... ]);
         */
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();