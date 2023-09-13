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
        return response()->json($genres, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:genres',
        ]);

        $genre = Genre::create([
            'name' => $request->input('name'),
        ]);

        return response()->json($genre, 201);
    }

    public function show($id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        return response()->json($genre, 200);
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);

        if (!$genre) {
            return response()->json(['message' => 'Genre not found'], 404);
        }

        $request->validate([
            'name' => 'required|unique:genres,name,' . $id,
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
}
