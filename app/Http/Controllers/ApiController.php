<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    public function index()
    {

        $perPage = request()->input('per_page', 3 ); // You can set a default value for per_page if needed
        $songs = Song::paginate($perPage);
    
        return response()->json($songs);
    }
}
