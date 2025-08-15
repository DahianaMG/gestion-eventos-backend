<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();;
        return response()->json($events);
    }

    public function eventsCreatedByUser(int $id)
    {
        $user = User::find($id);
        $events = $user->eventsCreated;

        return response()->json($events);
    }

    public function myEvents()
    {
        return $this->eventsCreatedByUser(auth()->id());
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
    public function store(EventRequest $request)
    {
        $userId = auth()->id();

        $event = Event::firstOrCreate([
            'user_id' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'date_time' => $request->date_time,
            'location' => $request->location,
            'has_fair' => $request->has_fair,
            'capacity' => $request->capacity
        ]);

        return response()->json([
            'message' => 'The event has been created.',
            'event' => $event
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $event = Event::with(['schedules', 'vendors'])->find($id);

        if (!$event) {
            return response()->json(['message' => 'Event not found.'], 404);
        }

        $schedules = $event->schedules->map(function ($schedule) {
            return [
                'activity_name' => $schedule->activity_name,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
            ];
        });

        $vendors = $event->vendors->map(function ($vendor) {
            return [
                'stand_name' => $vendor->stand_name,
                'stand_description' => $vendor->stand_description,
            ];
        });

        return response()->json([
            'user_id' => $event->user_id,
            'title' => $event->title,
            'description' => $event->description,
            'date_time' => $event->date_time,
            'location' => $event->location,
            'has_fair' => $event->has_fair,
            'capacity' => $event->capacity,
            'schedules' => $schedules,
            'vendors' => $vendors,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(EventRequest $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, int $id)
    {
        $event = Event::find($id);
        $user = auth()->user();

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event->update($request->only([
            'title',
            'description',
            'date_time',
            'location',
            'has_fair',
            'capacity',
        ]));

        return response()->json([
            'message' => 'The event has been updated.',
            'event' => $event
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $event = Event::find($id);
        $user = auth()->user();

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event->delete();

        return response()->json([
            'message' => 'The event has been deleted.'
        ]);
    }
}
