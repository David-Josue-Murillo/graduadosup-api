<?php

namespace App\Exceptions;

use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
use Log;

class ExceptionSetup 
{
    /**
     * Configura el manejo de excepciones personalizado.
     *
     * @param Exceptions $exceptions
     * @return void
     */
    public static function configure(Exceptions $exceptions)
    {
        // Configurar cada tipo de excepción llamando a métodos específicos
        self::handleModelNotFoundException($exceptions);
        self::handleQueryException($exceptions);
        self::handleGenericException($exceptions);
    }

    /**
     * Maneja ModelNotFoundException.
     *
     * @param Exceptions $exceptions
     * @return void
     */
    protected static function handleModelNotFoundException(Exceptions $exceptions)
    {
        $exceptions->render(function (ModelNotFoundException $exception, Request $request) {
            return response()->json(['message' => 'Registro no encontrado', 'error' => $exception->getMessage()], 404);
        });
    }

    /**
     * Maneja QueryException.
     *
     * @param Exceptions $exceptions
     * @return void
     */
    protected static function handleQueryException(Exceptions $exceptions)
    {
        $exceptions->render(function (QueryException $exception, Request $request) {
            Log::error('Error en la consulta de la base de datos: ' . $exception->getMessage());
            return response()->json(['message' => 'Error en la base de datos', 'error' => $exception->getMessage()], 500);
        });
    }

    /**
     * Maneja excepciones genéricas.
     *
     * @param Exceptions $exceptions
     * @return void
     */
    protected static function handleGenericException(Exceptions $exceptions)
    {
        $exceptions->render(function (Throwable $exception, Request $request) {
            Log::error('Error inesperado: ' . $exception->getMessage());
            return response()->json([
                'message' => 'El servidor no puede procesar la solicitud debido a un error del cliente', 
                'error' => $exception->getMessage()
            ], 422);
        });
    }
}

