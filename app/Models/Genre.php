<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    protected $fillable = ['name','user_id'];

    public function songs()
    {
        return $this->belongsToMany(Song::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
