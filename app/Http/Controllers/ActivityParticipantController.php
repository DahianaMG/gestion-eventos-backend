<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Models\ActivityParticipant;

class ActivityParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activity_participants = Schedule::with('participants')->get();
        return response()->json($activity_participants);
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
    public function store(ActivityParticipantRequest $request)
    {
        $user = $user = auth()->user();
        $schedule = Schedule::find($request->schedule_id);

        if ($user->role !== 'admin' && $schedule->event->user_id !== $user->id) {
            $userId = $user->id;
        } else {
            $userId = $request->user_id;
        }

        $participant = ActivityParticipant::firstOrCreate([
            'schedule_id' => $request->schedule_id,
            'user_id' => $userId,
        ], [
            'display_name' => $request->display_name,
        ]);

        return response()->json([
            'message' => 'Participant registered successfully.',
            'participant' => $participant
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $activity_participants = Schedule::with('participants')->find($id);
        return response()->json($activity_participants);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityParticipant $activityParticipant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActivityParticipantRequest $request, int $id)
    {
        $participant = ActivityParticipant::find($id);
        $user = auth()->user();
        $organizerId = $participant->schedule->event->user_id;

        if ($user->id !== $participant->user_id && $user->id !== $organizerId && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->id === $organizerId || $user->role === 'admin') {
            $participant->update($request->only(['display_name', 'user_id', 'schedule_id']));
        }
        else {
            $participant->update($request->only(['display_name']));
        }

        return response()->json([
            'message' => 'Participant updated successfully.',
            'participant' => $participant
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $participant = ActivityParticipant::findOrFail($id);
        $user = Auth::user();
        $eventOwnerId = $participant->schedule->event->user_id;

        if ($user->id !== $participant->user_id && $user->id !== $eventOwnerId && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $participant->delete();

        return response()->json(['message' => 'Participant removed.']);
    }
}
