<?php

namespace App\Http\Controllers;
use App\Models\License;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LicenseController extends Controller
{
    public function index()
    {
        $licenses = License::all();
        return response()->json( $licenses, 200 );
    }
    public function store( Request $request )
    {
        $license = License::create([
            "title"=>$request->input("title"),
            "description"=>$request->input("description"),
            "thumbnail"=>$request->input("thumbnail")
        ]);
        $license->save();
        return response()->json( $license, 200 );
    }
    public function show( License $license )
    {
        return response()->json( $license, 200 );
    }
    public function update( Request $request, License $license )
    {
        $license->update( $request->all() );
        return response()->json( $license, 200 );
    }
    public function destroy( License $license )
    {
        $license->delete();
        return response()->json( null, 204 );
    }


}
