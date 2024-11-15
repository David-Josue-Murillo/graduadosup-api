<?php

use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Api\CampuController;
use App\Http\Controllers\Api\GraduateController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\CareerController;
use App\Http\Controllers\Api\FacultyController;

// Routes the authentication
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/users', [UserController::class, 'store']);


Route::prefix('users')->group(function (){
    //CRUD Operations
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);   
    Route::put('/{id}', [UserController::class, 'update']); 
    Route::delete('/{id}', [UserController::class, 'destroy']); 

    // Other operations
    Route::patch('/{id}/update-password', [UpdatePasswordController::class, 'update']);
});

Route::prefix('faculties')->group(function () {
    // CRUD Operation
    Route::get('/', [FacultyController::class, 'index']);           
    Route::post('/', [FacultyController::class, 'store']);          
    Route::get('/{id}', [FacultyController::class, 'show']);   
    Route::put('/{faculty}', [FacultyController::class, 'update']); 
    Route::delete('/{faculty}', [FacultyController::class, 'destroy']); 

    // Relations operations
    Route::get('/{faculty}/career', [FacultyController::class, 'displayCareers']); 
});

Route::prefix('careers')->group(function () {
    // CRUD Operation
    Route::get('/', [CareerController::class, 'index']);           
    Route::post('/', [CareerController::class, 'store']);         
    Route::get('/{id}', [CareerController::class, 'show']);   
    Route::put('/{career}', [CareerController::class, 'update']); 
    Route::delete('/{career}', [CareerController::class, 'destroy']); 

    // Relations operations 
    Route::get('/{career}/faculty', [CareerController::class, 'displayFaculty']); 
});

Route::prefix('campus')->group(function () {
    // CRUD Operation
    Route::get('/', [CampuController::class, 'index']);          
    Route::post('/', [CampuController::class, 'store']);          
    Route::get('/{id}', [CampuController::class, 'show']);   
    Route::put('/{campu}', [CampuController::class, 'update']); 
    Route::delete('/{campu}', [CampuController::class, 'destroy']); 

    // Relations operations 
    Route::get('/{campu}/faculty', [CampuController::class, 'displayFaculty']); 
});

Route::prefix('graduates')->middleware(['auth:api'])->group(function () {
    // CRUD Operation
    Route::get('/', [GraduateController::class, 'index']);          
    Route::post('/', [GraduateController::class, 'store']);          
    Route::get('/{id}', [GraduateController::class, 'show']);   
    Route::put('/{graduates}', [GraduateController::class, 'update']); 
    Route::delete('/{graduates}', [GraduateController::class, 'destroy']); 

    // Relations operations 
    Route::get('/{graduates}/campus', [GraduateController::class, 'filterByCampus']); 
    Route::get('/{graduates}/career', [GraduateController::class, 'filterByCareer']); 
    Route::get('/{graduates}/faculty', [GraduateController::class, 'filterByFaculty']); 

    // By filters
});

// Emails
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail']);
Route::put('/reset-password', [PasswordResetController::class, 'reset']);