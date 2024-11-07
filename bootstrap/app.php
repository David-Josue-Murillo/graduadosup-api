<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
use Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Manejo de ModelNotFoundException
        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        });

        // Manejo de QueryException
        $exceptions->render(function (QueryException $exception, Request $request) {
            Log::error('Error en la consulta de la base de datos: ' . $exception->getMessage());
            return response()->json(['message' => 'Error en la base de datos'], 500);
        });

        // Manejo de otras excepciones genéricas
        $exceptions->render(function (Throwable $exception, Request $request) {
            Log::error('Error inesperado: ' . $exception->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        });
    })->create();
