<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Requests\RegistrationRequest;

class RegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $registrations = Registration::all();
        return response()->json($registrations);
    }

    public function attendingEventsByUser(int $id)
    {
        $user = User::find($id);
        $registrations = $user->attendingEvents;

        return response()->json($registrations);
    }

    public function myAttendingEvents()
    {
        return $this->attendingEventsByUser(auth()->id());
    }

    public function registrationsByEvent(int $eventId)
    {
        $event = Event::with('registrations.user')->find($eventId);

        return response()->json([
            'event' => $event->title,
            'registrations' => $event->registrations
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegistrationRequest $request)
    {
        if (auth()->user()->role === 'admin') {
            $userId = $request->user_id;
            $status = $request->status;
        } else {
            $userId = auth()->id();
            $status = "Pending";

        }

        $registration = Registration::firstOrCreate([
            'user_id' => $userId,
            'event_id' => $request->event_id,
            'role_in_event' => $request->role_in_event,
            'status' => $status
        ]);

        return response()->json([
            'message' => 'Event registration completed successfully.',
            'registration' => $registration
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $registration = Registration::find($id);
        return response()->json($registration);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(RegistrationRequest $registration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RegistrationRequest $request, int $id)
    {
        $registration = Registration::find($id);
        $user = auth()->user();

        if ($registration->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'admin') {
            $registration->user_id = $request->user_id;
            $registration->event_id = $request->event_id;
            $registration->status = $request->status;
        }

        $registration->update($request->only([
            'role_in_event',
        ]));

        return response()->json([
            'message' => 'The event registration has been updated.',
            'registration' => $registration
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $registration = Registration::find($id);
        $user = auth()->user();

        if ($registration->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $registration->delete();

        return response()->json([
            'message' => 'Event registration canceled successfully.'
        ]);
    }
}
