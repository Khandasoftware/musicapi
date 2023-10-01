<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order ): bool
    {
        //User must be an Artist or a Producer and must be owner
        return ( $user->isArtist() || $user->isProducer() ) && ( $user->id === $order->user_id );
    }
}
