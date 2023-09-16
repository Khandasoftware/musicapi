<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GenresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $genreNames = ['Rock', 'Pop', 'Hip-Hop', 'Jazz', 'Country'];
        foreach( $genreNames as $name )
            Genre::create( [ 
                "name"=>$name, 
            ] );
    }
}
