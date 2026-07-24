<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\WorkoutPlanController;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Auth\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Public routes - no authentication required
Route::post('/register', Register::class);
Route::post('/login', Login::class);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', Logout::class);

    // Client routes
    Route::apiResource('clients', ClientController::class);

    // Exercises routes
    Route::apiResource('exercises', ExerciseController::class);

    // Workout plans routes
    Route::apiResource('workout-plans', WorkoutPlanController::class);
});