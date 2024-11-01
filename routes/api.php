<?php

use App\Http\Controllers\api\FacultyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('faculty')->group(function () {
    Route::get('/all', [FacultyController::class, 'index']);
    Route::post('/create', [FacultyController::class, 'store']);
    Route::get('/search/{id}', [FacultyController::class, 'show']);
    Route::put('/update/{faculty}', [FacultyController::class, 'update']);
});