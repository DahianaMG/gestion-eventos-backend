<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Vendor;
use App\Models\Schedule;
use App\Models\ActivityParticipant;
use App\Http\Requests\VoteRequest;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    //helper functions for results

    //Display name based on type
    protected function getDisplayName($target)
    {
        if (!$target) return null;
        if ($target instanceof Vendor) {
            return $target->stand_name;
        } elseif ($target instanceof Schedule) {
            return $target->activity_name;
        } elseif ($target instanceof ActivityParticipant) {
            return $target->display_name;
        } else{
            return null;
        }
    }

    //Count votes by target type (Vendor, Schedule, ActivityParticipant)
    protected function countVotesForType(int $eventId, string $type)
    {
        return Vote::where('event_id', $eventId)
            ->where('target_type', $type)
            ->selectRaw('target_id, COUNT(*) as votes')
            ->groupBy('target_id')
            ->get()
            ->map(function ($item) use ($type) {
                $target = $type::find($item->target_id);
                return [
                    'target_id' => $item->target_id,
                    'votes'     => $item->votes,
                    'name'      => $this->getDisplayName($target),
                ];
            })
            ->sortByDesc('votes')
            ->values()
            ->toArray();
    }

    /**
     * Get voting results for a specific event
     */
    public function results($eventId)
    {
        $event = Event::find($eventId);

        return response()->json([
            'vendors' => $this->countVotesForType($eventId, Vendor::class),
            'schedules' => $this->countVotesForType($eventId, Schedule::class),
            'participants' => $this->countVotesForType($eventId, ActivityParticipant::class),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //helper functions for results

    //Check if user has voted for this specific target
    protected function hasAlreadyVoted(int $userId, int $eventId, string $type, int $targetId)
    {
        return Vote::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('target_type', $type)
            ->where('target_id', $targetId)
            ->exists();
    }

    //Check if user has already voted in this category (Vendor or Schedule)
    protected function hasVotedInCategory(int $userId, int $eventId, string $type)
    {
        return Vote::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('target_type', $type)
            ->exists();
    }

    //Check if user already voted for a participant in the same activity
    protected function hasVotedInSameActivity(int $userId, int $eventId, int $participantId)
    {
        $participant = ActivityParticipant::find($participantId);

        if (!$participant) {
            return false;
        }

        $scheduleId = $participant->schedule_id;

        return Vote::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->where('target_type', ActivityParticipant::class)
            ->whereIn('target_id', function ($query) use ($scheduleId) {
                $query->select('id')
                    ->from('activity_participants')
                    ->where('schedule_id', $scheduleId);
            })
            ->exists();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoteRequest $request)
    {
        $user = $request->user();
        $eventId = $request->event_id;
        $targetType = $request->target_type;
        $targetId = $request->target_id;

        //Only one vote per item
        if ($this->hasAlreadyVoted($user->id, $eventId, $targetType, $targetId)) {
            return response()->json(['message' => 'You have already voted for this.'], 409);
        }

        //Only one vote per stand or activity per event
        if (in_array($targetType, [Vendor::class, Schedule::class])) {
            if ($this->hasVotedInCategory($user->id, $eventId, $targetType)) {
                return response()->json(['message' => 'You have already voted in this category.'], 409);
            }
        }

        //Only one vote per activity (cosplayer, etc.)
        if ($targetType === ActivityParticipant::class) {
            if ($this->hasVotedInSameActivity($user->id, $eventId, $targetId)) {
                return response()->json(['message' => 'You have already voted in this activity.'], 409);
            }
        }

        $vote = Vote::create([
            'user_id'     => $user->id,
            'event_id'    => $eventId,
            'target_type' => $targetType,
            'target_id'   => $targetId,
            'vote_time'   => now(),
        ]);

        return response()->json([
            'message' => 'Vote registered successfully.',
            'vote' => $vote,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vote $vote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vote $vote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vote $vote)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vote $vote)
    {
        //
    }
}
