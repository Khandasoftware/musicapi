<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    //
    function show() {
        Log::info('The logging is working!!!'.env('STRIPE_WEBHOOK_SECRET') );
        return response()->json( [ 'logging' => true ]);
    }
}
