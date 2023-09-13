<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB; // Add this line to import the DB facade

class SongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $songs = [
            [
                'title' => 'Song 1',
                'description' => 'Description for Song 1',
                'artist' => 'Artist 1',
                'mp3' => 'https://example.com/song1.mp3',
                'cover_image' => 'https://example.com/cover1.jpg',
        
            ],
            [
                'title' => 'Song 2',
                'description' => 'Description for Song 2',
                'artist' => 'Artist 2',
                'mp3' => 'https://example.com/song2.mp3',
                'cover_image' => 'https://example.com/cover2.jpg',
            ],
            [
                'title' => 'Song 3',
                'description' => 'Description for Song 3',
                'artist' => 'Artist 3',
                'mp3' => 'https://example.com/song3.mp3',
                'cover_image' => 'https://example.com/cover3.jpg',
            ],
            [
                'title' => 'Song 3',
                'description' => 'Description for Song 3',
                'artist' => 'Artist 3',
                'mp3' => 'https://example.com/song3.mp3',
                'cover_image' => 'https://example.com/cover3.jpg',
            ],
            // Add more songs as needed
        ];

        foreach ($songs as $song) {
            DB::table('songs')->insert($song);
        }
    }
}
