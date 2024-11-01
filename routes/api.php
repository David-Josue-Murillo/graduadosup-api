<?php

use App\Http\Controllers\api\FacultyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/faculties', [FacultyController::class, 'index']);
Route::get('/faculty/create', [FacultyController::class, 'store']);