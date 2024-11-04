<?php

use App\Http\Controllers\api\CampuController;
use App\Http\Controllers\api\GraduateController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\CareerController;
use App\Http\Controllers\api\FacultyController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('faculties')->group(function () {
    // CRUD Operation
    Route::get('/', [FacultyController::class, 'index']);           
    Route::post('/', [FacultyController::class, 'store']);          
    Route::get('/{id}', [FacultyController::class, 'show']);   
    Route::put('/{faculty}', [FacultyController::class, 'update']); 
    Route::delete('/{faculty}', [FacultyController::class, 'destroy']); 

    // Relations operations
    Route::get('/{faculty}/careers', [FacultyController::class, 'displayCareers']); 
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

Route::prefix('graduates')->group(function () {
    // CRUD Operation
    Route::get('/', [GraduateController::class, 'index']);          
    Route::post('/', [GraduateController::class, 'store']);          
    Route::get('/{id}', [GraduateController::class, 'show']);   
    Route::put('/{graduates}', [GraduateController::class, 'update']); 
    Route::delete('/{graduates}', [GraduateController::class, 'destroy']); 

    // Relations operations 
    Route::get('/{graduates}/campus', [GraduateController::class, 'displayCampus']); 
    Route::get('/{graduates}/career', [GraduateController::class, 'displayCareer']); 
    Route::get('/{graduates}/faculty', [GraduateController::class, 'displayFaculty']); 
});

