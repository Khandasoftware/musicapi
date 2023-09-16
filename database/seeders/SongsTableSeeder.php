<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SongsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //10 entries
        for( $i = 1; $i <= 10; $i++ ){
            Song::create([
                'title'=>"Song $i",
                'description' => "Description for Song $i",
                'artist' => "Artist $i",
                'mp3' => "https://example.com/song$i.mp3",
                'cover_image' => "https://example.com/cover$i.jpg",
            ]);
        }
    }
}
