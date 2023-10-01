<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Traits\FilterTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    use FilterTrait;
    public function index()
    {
        return response()->json(Order::all(), 200 );
    }
    public function show(Order $order)
    {
        //user access check
        try {
            $this->authorize('view', $order );
        } catch (AuthorizationException $e) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        
        return response()->json( $order, 200);

    }
}
