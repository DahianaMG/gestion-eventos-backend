<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Vendor;
use Illuminate\Http\Request;
use App\Http\Requests\VendorRequest;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($eventId)
    {
        $vendors = Vendor::where('event_id', $eventId)->with('user:id,name')->get();
        return response()->json($vendors);
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
    public function store(VendorRequest $request)
    {
        $user = auth()->user();
        $event = Event::find($request->event_id);

        if ($event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $vendor = Vendor::firstOrCreate([
            'user_id'          => $request->user_id,
            'event_id'         => $request->event_id,
            'stand_name'       => $request->stand_name,
            'stand_description'=> $request->stand_description,
            'stand_location'   => $request->stand_location,
        ]);

        return response()->json([
            'message' => 'The stand has been created.',
            'vendor'  => $vendor
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VendorRequest $request, int $id)
    {
        $vendor = Vendor::find($id);
        $user = auth()->user();
        $event = Event::find($vendor->event_id);

        if ($vendor->user_id !== $user->id && $event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($event->user_id === $user->id || $user->role === 'admin') {
            $vendor->user_id = $request->user_id;
            $vendor->event_id = $request->event_id;
            $vendor->stand_location = $request->stand_location;
        }

        $vendor->update($request->only([
            'stand_name',
            'stand_description',
        ]));

        return response()->json([
            'message' => 'The stand has been updated.',
            'vendor'  => $vendor
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $vendor = Vendor::find($id);
        $user = auth()->user();
        $event = Event::find($request->event_id);

        if ($vendor->user_id !== $user->id && $event->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $vendor->delete();

        return response()->json([
            'message' => 'The stand has been deleted.'
        ]);
    }
}
