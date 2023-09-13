<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SongController extends Controller
{
    public function index()
    {
        return Song::all();
    }
    
    public function store(Request $request)
    {
        $song = Song::create($request->all());
        $song->genres()->sync($request->input('genres'));
        return response()->json($song, 201);
    }
    
    public function show(Song $song)
    {
        return $song;
    }
    
    public function update(Request $request, Song $song)
    {
        $song->update($request->all());
        $song->genres()->sync($request->input('genres'));
        return response()->json($song, 200);
    }
    
    public function destroy(Song $song)
    {
        $song->delete();
        return response()->json(null, 204);
    }
}
