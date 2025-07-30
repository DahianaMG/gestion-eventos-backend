<?php

namespace App\Http\Controllers;

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
        if (auth()->user()->role === 'admin') {
            $userId = $request->user_id;
            $standLocation = $request->stand_location;
        } else {
            $userId = auth()->id();
            $standLocation = "To assign";
        }

        $vendor = Vendor::firstOrCreate([
            'user_id'          => $userId,
            'event_id'         => $request->event_id,
            'stand_name'       => $request->stand_name,
            'stand_description'=> $request->stand_description,
            'stand_location'   => $standLocation,
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

        if ($vendor->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($user->role === 'admin') {
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

        if ($vendor->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $vendor->delete();

        return response()->json([
            'message' => 'The stand has been deleted.'
        ]);
    }
}
