<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'artist', 'mp3', 'cover_image'];

    public function genres()
    {
        return $this->belongsToMany(Genre::class);
    }
}
