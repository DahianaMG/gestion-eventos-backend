<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Requests\PhotoRequest;

class PhotoController extends Controller
{
    //Get photos
    public function index(Request $request)
    {
        $query = Photo::query();

        //Optional filter by one or more tags
        foreach ($request->input('tags', []) as $tag) {
            $query->whereJsonContains('tags', $tag);
        }

        $photos = $query->orderByDesc('uploaded_at')->get();

        return response()->json($photos);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    //Upload photo
    public function store(PhotoRequest $request)
    {
        $path = $request->file('photo')->store('photos', 'public'); //saves in storage/app/public/photos

        $photo = Photo::create([
            'user_id'     => $request->user()->id,
            'event_id'    => $request->event_id,
            'photo_url'   => '/storage/' . $path,
            'description' => $request->description,
            'uploaded_at' => now(),
            'tags'        => $request->tags, //array
        ]);

        return response()->json([
            'message' => 'Photo uploaded successfully.',
            'photo'   => $photo
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Photo $photo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Photo $photo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Photo $photo)
    {
        //
    }

    //Delete a photo (only the owner or admin)
    public function destroy(int $id)
    {
        $photo = Photo::find($id);

        $user = $request->user();

        if ($photo->user_id !== $user->id && $user->role !== 'admin') {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $photo->delete();

        return response()->json(['message' => 'Photo deleted.']);
    }
}
