<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use App\Models\Song;
use App\Models\Order;
use App\Models\License;
use Stripe\PaymentIntent;
use Illuminate\Http\Request;
use App\Services\SongService;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Storage;

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
         //user access check
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
        $user = auth()->user();
        
        $song = new Song($request->all());
        //user access check
        try {
            $this->authorize('create', $song );
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
       
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'artist' => 'required|string',
            'audio' => 'required|file|mimes:application/octet-stream,audio/mpeg,mpga,mp3|max:15000', // Max size in kilobytes (15 megabytes)
            'cover_image' => 'nullable|image|max:2000', // Max size in kilobytes (2 megabytes)
            'price' => 'required|numeric',
        ]);

        $song->user_id = $user->id;

        // Sync the genres
        $song->genres()->sync($request->input('genres'));

        // Associate the song with a license using sync
        $licenseId = $request->input('license_id');
        $license = License::find($licenseId);
        $song->license()->associate($license);

        // Upload image
        if( !empty( $request->cover_image ) ){
            $coverImage = $this->sanitizeFileName($request->cover_image->getClientOriginalName()).'-'.time().'.' . $request->cover_image->extension();
            $request->cover_image->move(public_path('images'), $coverImage);
            $song->cover_image = $coverImage;
        }

        // Upload audio file
        $audio = $request->audio;
        $originalAudioFileName = $this->sanitizeFileName(pathinfo( $audio->getClientOriginalName(), PATHINFO_FILENAME) );
        $audioFileName =  $originalAudioFileName.'-'.time().'.'. $audio->getClientOriginalExtension();
        $audio->move(public_path('audio'), $audioFileName);
        $song->audio = $audioFileName;

        //finally save the song
        $song->save();
        
        return response()->json($song, 201);
    }
     
    public function show(Song $song)
    {
         //user access check
        try {
            $this->authorize('view', $song );
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json( $song, 200);

    }
    
    public function update(Request $request, Song $song)
    {
        //user access check
        try {
            $this->authorize('update', $song);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $song->update($request->all());
        $song->genres()->sync($request->input('genres'));
        return response()->json($song, 200);
    }
    
    public function restore(Song $song)
    {
        //user access check
        try {
            $this->authorize('restore', $song);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        // Restore the song (set deleted_at to null).
        $song->restore();

        return response()->json($song, 200);
    }
    public function destroy(Song $song)
    {
        try {
            $this->authorize('delete', $song);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $song->delete();
        return response()->json(null, 204);
    }

    public function destroyPermanant(Song $song)
    {
         //user access check
        try {
            $this->authorize('forceDelete', $song);
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $song->forceDelete();
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

    public function songsByLicense($licenseId)
    {
        // Retrieve songs for the specified license
        $songs = Song::whereHas('license', function ($query) use ($licenseId) {
            $query->where('license_id', $licenseId);
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

        $order = new Order();
        $order->user_id = auth()->id();
        $order->product_id = $song->id; // Assuming you have the product object
        //$order->quantity = $quantity; // The quantity of the product
        $order->stripe_payment_intent_id = $paymentIntent->id;
        $order->status = 'pending'; // Initial status
        $order->price = intval($song->price); // Set the price based on the product's price
        $order->save();

        return response()->json([
            'client_secret' => $paymentIntent->client_secret,
            'payment_id' => $paymentIntent->id
        ]);
    }


    public function confirmPayment(Request $request)
    {
        $paymentIntentId = $request->input('stripe_payment_intent_id');
    
        // Retrieve the PaymentIntent from Stripe
        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
    
        // Check if the payment was successful
        if ($paymentIntent->status === 'succeeded') {
            // Update the payment record status in your database
            $payment = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
            $payment->status = 'completed';
            $payment->save();
    
            // Perform any additional actions (e.g., send confirmation email, update user account, etc.)
    
            return response()->json(['message' => 'Payment confirmed successfully']);
        }
    
        return response()->json(['message' => 'Payment confirmation failed']);
    }
    
    private function sanitizeFileName($fileName)
    {
        return preg_replace('/[^\w]/', '-', $fileName);
    }
     
}
