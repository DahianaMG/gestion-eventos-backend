<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Requests\ScheduleRequest;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($eventId)
    {
        $schedules = Schedule::where('event_id', $eventId)
        ->orderBy('start_time', 'asc')
        ->get();
        return response()->json($schedules);
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
    public function store(ScheduleRequest $request)
    {
        $user = auth()->user();
        $event = Event::find($request->event_id);

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $schedule = Schedule::firstOrCreate([
            'event_id' => $request->event_id,
            'activity_name' => $request->activity_name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location_description' => $request->location_description
        ]);

        return response()->json([
            'message' => 'The schedule has been created.',
            'schedule' => $schedule
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        return Schedule::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ScheduleRequest $request, int $id)
    {
        $user = auth()->user();
        $schedule = Schedule::find($id);
        $event = Event::find($schedule->event_id);

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $schedule->update($request->only([
            'activity_name',
            'start_time',
            'end_time',
            'location_description'
        ]));

        return response()->json([
            'message' => 'The schedule has been updated.',
            'schedule' => $schedule
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $user = auth()->user();
        $schedule = Schedule::find($id);
        $event = Event::find($schedule->event_id);

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $schedule->delete();

        return response()->json([
            'message' => 'The schedule has been deleted.'
        ]);
    }
}
