<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GenreController extends Controller
{
    //
    public function index()
    {
        $genres = Genre::all();
        $user = auth()->user();
        // Logic for filtering by users here
        if( $user && in_array( $user->role, [ "artist" , "producer"] ) )
            $genres = $user->genres;

        return response()->json($genres, 200);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        //user access check
        try {
            $this->authorize('create', Genre::class );
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|unique:genres',
        ]);

        $genre = Genre::create([
            'name' => $request->input('name'),
            'user_id' => $user->id
        ]);

        return response()->json($genre, 201);
    }


    public function show( Genre $genre )
    {
         //user access check
        try {
            $this->authorize('view', $genre );
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$genre) {
            return response()->json(['message' => 'Genre not found'], 404);
        }
        return $genre;


    }

    public function update(Request $request, Genre $genre )
    {
         //user access check
        try {
            $this->authorize('update', $genre );
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|unique:genres,name,' . $genre->id,
        ]);

        $genre->name = $request->input('name');
        $genre->save();

        return response()->json($genre, 200);
    }

    public function destroy($id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        $genre->delete();

        return response()->json(null, 204);
    }
    public function destroyPermanant(Song $song)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        $genre->forceDelete();

        return response()->json(null, 204);
    }
}
