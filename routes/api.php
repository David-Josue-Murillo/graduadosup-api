<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\UpdatePasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\CampuController;
use App\Http\Controllers\Api\GraduateController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\CsvController;

// Routes the authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/users', [UserController::class, 'store']);

// Emails
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::put('/reset-password', [PasswordResetController::class, 'reset']);

Route::apiResource('users', UserController::class)->middleware('auth:api');
Route::patch('/user/update-password', [UpdatePasswordController::class, 'update'])->middleware(['auth:api']);

Route::apiResource('faculties', FacultyController::class);
Route::get('faculties/{faculty}/career', [FacultyController::class, 'displayCareers']);

Route::apiResource('careers', CareerController::class);
Route::get('careers/{career}/faculty', [CareerController::class, 'displayFaculty']);

Route::apiResource('campus', CampuController::class);

Route::apiResource('graduates', GraduateController::class);
Route::get('graduates/{graduates}/campus', [GraduateController::class, 'filterByCampus']);
Route::get('graduates/{graduates}/career', [GraduateController::class, 'filterByCareer']);
Route::get('graduates/{graduates}/faculty', [GraduateController::class, 'filterByFaculty']);

// Get data
Route::get('/process-local-data', [CsvController::class, 'processCsv']);