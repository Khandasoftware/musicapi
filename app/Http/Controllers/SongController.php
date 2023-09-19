<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Song;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Services\SongService;
use App\Http\Controllers\Controller;

class SongController extends Controller
{
    protected $songService;

    public function __construct(SongService $songService) {
        $this->songService = $songService;
    }

    public function index()
    {
        $perPage = request()->input('per_page', 10);
        $orderColumn = request()->input('order_column', 'title'); // Default order column is 'title'
        $orderDirection = request()->input('order_direction', 'asc'); // Default order direction is 'asc'
        
        try {
            $songs = $this->songService->getPaginatedAndOrderedSongs(
                $perPage,
                $orderColumn,
                $orderDirection
            );
            return response()->json($songs);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
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

    public function getByGenre($genre)
    {
        // Retrieve songs that belong to the specified genre
        $songs = Song::whereHas('genres', function ($query) use ($genre) {
            $query->where('name', $genre);
        })->get();
    
        return response()->json(['songs' => $songs]);
    }



    public function purchase(Request $request, Song $song)
    {

        Stripe::setApiKey('sk_test_51NrnCwFBkZkLD0wZ9RVAcjR9kMa4Ommvp7ejNlWaShFPG3BRpj9h2Z4KEYL9vXRKLbAPHFdcjpl4nlhRLbhdKz9K00rDRDZ9nR');

        // Create a PaymentIntent
        $paymentIntent = PaymentIntent::create([
            'amount' => intval($song->price) * 100, // Amount in cents
            'currency' => 'usd', // Change to your desired currency
            'description' => $song->title,
            'payment_method_types' => ['card'],
        ]);

        return response()->json(['client_secret' => $paymentIntent->client_secret]);
    }
    
}
