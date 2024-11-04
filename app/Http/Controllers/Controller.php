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

    public function displayByTable($model, $table, $id) {
        $data = $model::with($table)->findOrFail($id);
        return $data->$table()->get();
    }
}
