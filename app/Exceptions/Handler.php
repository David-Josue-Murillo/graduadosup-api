<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Throwable;
use Log;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Manejo de ModelNotFoundException para entidades no encontradas
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Registro de graduado no encontrado'], 404);
        }

        // Manejo de errores de consulta
        if ($exception instanceof QueryException) {
            Log::error('Error en la consulta de la base de datos: ' . $exception->getMessage());
            return response()->json(['message' => 'Error al consultar la base de datos'], 500);
        }

        // Manejo de errores genéricos
        if ($exception instanceof Exception) {
            Log::error('Error inesperado: ' . $exception->getMessage());
            return response()->json(['message' => 'Error interno del servidor'], 500);
        }

        return parent::render($request, $exception);
    }
}

