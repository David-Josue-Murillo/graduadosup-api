<?php

use App\Exceptions\ExceptionSetup;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(prepend:[
            \App\Http\Middleware\ForceJsonResponseMiddleware::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        ExceptionSetup::configure($exceptions);
    })->create();
