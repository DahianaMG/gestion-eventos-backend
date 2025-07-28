<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function eventsCreatedByUser(int $id)
    {
        $user = User::find($id);
        $events = $user->createdEvents;

        return response()->json($events);
    }

    public function myEvents()
    {
        return $this->createdEvents(auth()->id());
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
    public function store(Request $request)
    {
        $userId = auth()->id();

        if ($event->user_id !== $userId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event = Event::firstOrCreate([
            'user_id' => $userId,
            'title' => $request->title,
            'description' => $request->description,
            'date_time' => $request->date_time,
            'location' => $request->location,
            'has_fair' => $request->boolean('has_fair'),
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
        $event = Event::find($id);
        return response()->json($event);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $userId = auth()->id();

        if ($event->user_id !== $userId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event = Event::find($id);
        $event->update($request->only([
            'user_id',
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
        $userId = auth()->id();

        if ($event->user_id !== $userId) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event = Event::find($id);
        $event->delete();
        return response()->json([
            'message' => 'The event has been deleted.'
        ]);
    }
}
