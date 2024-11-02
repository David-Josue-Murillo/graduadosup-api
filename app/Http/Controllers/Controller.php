<?php

namespace App\Http\Controllers;

abstract class Controller
{
    //
    public function jsonResponse($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }
}
