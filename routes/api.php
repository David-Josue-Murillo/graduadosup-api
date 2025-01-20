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

// Routes the authentication
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/register', [RegisterController::class, 'register'])->middleware('throttle:5,1');

// Emails
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->middleware('throttle:5,1');
Route::put('/reset-password', [PasswordResetController::class, 'reset'])->middleware('throttle:10,1');

Route::apiResource('users', UserController::class)->middleware(['auth:api', 'throttle:60,1']);
Route::patch('/user/update-password', [UpdatePasswordController::class, 'update'])->middleware(['auth:api', 'throttle:5,1']);

Route::apiResource('faculties', FacultyController::class)->middleware('throttle:160,1');
Route::get('faculties/{faculty}/career', [FacultyController::class, 'displayCareers'])->middleware('throttle:160,1');

Route::apiResource('careers', CareerController::class)->middleware('throttle:160,1');
Route::get('careers/{career}/faculty', [CareerController::class, 'displayFaculty'])->middleware('throttle:160,1');

Route::apiResource('campus', CampuController::class)->middleware('throttle:160,1');

Route::apiResource('graduates', GraduateController::class)->middleware('throttle:160,1');
Route::get('graduates-all', [GraduateController::class, 'allDataGraduates'])->middleware('throttle:10,1');
Route::get('graduates/{graduates}/campus', [GraduateController::class, 'filterByCampus'])->middleware('throttle:160,1');
Route::get('graduates/{graduates}/career', [GraduateController::class, 'filterByCareer'])->middleware('throttle:160,1');
Route::get('graduates/{graduates}/faculty', [GraduateController::class, 'filterByFaculty'])->middleware('throttle:160,1');