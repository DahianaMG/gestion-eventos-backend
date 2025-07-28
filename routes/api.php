<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\API\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Events
    Route::get('get-events', [EventController::class, 'index']);
    Route::get('events-by-user/{id}', [EventController::class, 'eventsCreatedByUser']);
    Route::get('my-events', [EventController::class, 'myEvents']);
    Route::post('set-event', [EventController::class, 'store']);
    Route::get('get-event/{id}', [EventController::class, 'show']);
    Route::put('update-event/{id}', [EventController::class, 'update']);
    Route::delete('delete-event/{id}', [EventController::class, 'destroy']);
});


