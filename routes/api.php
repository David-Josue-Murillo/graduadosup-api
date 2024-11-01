<?php

use App\Http\Controllers\api\FacultyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('faculties')->group(function () {
    Route::get('/', [FacultyController::class, 'index']);           // GET /faculties - Lista todas las facultades
    Route::post('/', [FacultyController::class, 'store']);          // POST /faculties - Crea una nueva facultad
    Route::get('/{faculty}', [FacultyController::class, 'show']);   // GET /faculties/{id} - Muestra una facultad específica
    Route::put('/{faculty}', [FacultyController::class, 'update']); // PUT /faculties/{id} - Actualiza una facultad específica
    Route::delete('/{faculty}', [FacultyController::class, 'destroy']); // DELETE /faculties/{id} - Elimina una facultad específica
});