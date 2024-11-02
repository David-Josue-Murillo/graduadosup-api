<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CareerController;
use App\Http\Controllers\api\FacultyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('faculties')->group(function () {
    Route::get('/', [FacultyController::class, 'index']);           // GET /faculties - Lista todas las facultades
    Route::post('/', [FacultyController::class, 'store']);          // POST /faculties - Crea una nueva facultad
    Route::get('/{id}', [FacultyController::class, 'show']);   // GET /faculties/{id} - Muestra una facultad específica
    Route::put('/{faculty}', [FacultyController::class, 'update']); // PUT /faculties/{id} - Actualiza una facultad específica
    Route::delete('/{faculty}', [FacultyController::class, 'destroy']); // DELETE /faculties/{id} - Elimina una facultad específica
});

Route::prefix('careers')->group(function () {
    Route::get('/', [CareerController::class, 'index']);           // GET /career - Lista todas las carreras
    Route::post('/', [CareerController::class, 'store']);          // POST /career - Crea una nueva carrera
    Route::get('/{id}', [CareerController::class, 'show']);   // GET /career/{id} - Muestra una carrera específica
    Route::put('/{career}', [CareerController::class, 'update']); // PUT /career/{id} - Actualiza una carrera específica
    Route::delete('/{career}', [CareerController::class, 'destroy']); // DELETE /career/{id} - Elimina una carrera específica
});

