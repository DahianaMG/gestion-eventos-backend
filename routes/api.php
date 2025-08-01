<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ActivityParticipantController;

//->Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//Events
Route::get('get-events', [EventController::class, 'index']);
Route::get('get-event/{id}', [EventController::class, 'show']);

//->Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //Events
    Route::get('my-events', [EventController::class, 'myEvents']); //organizer
    Route::post('set-event', [EventController::class, 'store']);
    Route::put('update-event/{id}', [EventController::class, 'update']); //organizer
    Route::delete('delete-event/{id}', [EventController::class, 'destroy']); //organizer

    //Registrations
    Route::get('my-attending-events', [RegistrationController::class, 'myAttendingEvents']);
    Route::get('get-registrations-by-event/{id}', [RegistrationController::class, 'registrationsByEvent']); //organizer
    Route::post('set-registration', [RegistrationController::class, 'store']);
    Route::get('get-registration/{id}', [RegistrationController::class, 'show']); //organizer
    Route::put('update-registration/{id}', [RegistrationController::class, 'update']); //organizer
    Route::delete('delete-registration/{id}', [RegistrationController::class, 'destroy']);

    //Schedules
    Route::get('get-schedules-by-event/{event}', [ScheduleController::class, 'index']);
    Route::post('set-schedule', [ScheduleController::class, 'store']);  //organizer
    Route::put('update-schedule/{id}', [ScheduleController::class, 'update']);  //organizer
    Route::delete('delete-schedule/{id}', [ScheduleController::class, 'destroy']);  //organizer

    //Vendors
    Route::get('get-vendors-by-event/{event}', [VendorController::class, 'index']);
    Route::post('set-vendor', [VendorController::class, 'store']); //organizer
    Route::put('update-vendor/{id}', [VendorController::class, 'update']);
    Route::delete('delete-vendor/{id}', [VendorController::class, 'destroy']);

    //Activity participants
    Route::get('get-activity-participants', [ActivityParticipantController::class, 'index']);
    Route::post('set-activity-participant', [ActivityParticipantController::class, 'store']);
    Route::get('get-activity-participants-by-activity/{id}', [ActivityParticipantController::class, 'show']);
    Route::put('update-activity-participant/{id}', [ActivityParticipantController::class, 'update']);
    Route::delete('delete-activity-participant/{id}', [ActivityParticipantController::class, 'destroy']);
});

//->Admin routes
Route::middleware('auth:sanctum', 'role:admin')->group(function () {
    //Events
    Route::get('events-by-user/{id}', [EventController::class, 'eventsCreatedByUser']);

    //Registrations
    Route::get('get-registrations', [RegistrationController::class, 'index']);
    Route::get('get-attending-events-by-user/{id}', [RegistrationController::class, 'attendingEventsByUser']);
});
