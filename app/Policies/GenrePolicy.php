<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Genre;

class GenrePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Genre $genre): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $genre->user_id );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //User must be artist or producer
        return $user->isArtist() || $user->isProducer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Genre $genre): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $genre->user_id );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Genre $genre): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $genre->user_id );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Genre $genre): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $genre->user_id );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Genre $genre): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $genre->user_id );
    }
}
