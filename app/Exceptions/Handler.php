<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Exception;
use Log;

class Handler extends ExceptionHandler
{
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json(['message' => 'Registro no encontrado'], 404);
        }

        if ($exception instanceof QueryException) {
            Log::error($exception->getMessage());
            return response()->json(['message' => 'Error de base de datos'], 500);
        }

        return response()->json(['message' => $exception->getMessage()], $exception->getCode() ?: 500);
    }
}

