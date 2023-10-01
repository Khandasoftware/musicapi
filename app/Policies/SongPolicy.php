<?php

namespace App\Policies;

use App\Models\Song;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SongPolicy
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
    public function view(User $user, Song $song ): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $song->user_id );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create( User $user ): bool
    {
        //User must be owner
        return $user->isArtist() || $user->isProducer();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Song $song): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $song->user_id );
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Song $song): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $song->user_id );
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Song $song): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $song->user_id );
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Song $song): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $song->user_id );
    }
}
