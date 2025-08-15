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
        $user = auth()->user();
        $event = Event::with('registrations.user')->find($eventId);

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

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
            $status = "pending";

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
        $registration = Registration::with(['user', 'event'])->find($id);

        if (!$registration) {
            return response()->json(['message' => 'Inscripción no encontrada'], 404);
        }

        return response()->json([
            'id' => $registration->id,
            'role_in_event' => $registration->role_in_event,
            'status' => $registration->status,
            'created_at' => $registration->created_at,
            'user' => [
                'id' => $registration->user->id,
                'name' => $registration->user->name,
            ],
            'event' => [
                'id' => $registration->event->id,
                'organizer_id' => $registration->event->user_id,
                'title' => $registration->event->title,
            ]
        ]);
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
        $event = Event::find($registration->event_id);

        if ($registration->user_id !== $user->id && $event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'admin') {
            $registration->update($request->only([
                'user_id',
                'event_id',
                'status',
                'role_in_event'
            ]));
        }
        elseif ($event->user_id === $user->id) {
            $registration->update($request->only(['status']));
        }
        else {
            $registration->update($request->only(['role_in_event']));
        }

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
