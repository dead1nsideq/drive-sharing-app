<?php

namespace App\Policies;

use App\Models\Trip;
use App\Models\User;

class TripPolicy
{
    /**
     * Determine whether the user can access the model.
     */
    public function access(User $user, Trip $trip): bool
    {
        return ($trip->user->id === $user->id) || ($trip->driver?->id === $user->driver?->id);
    }

    public function handle(User $user, Trip $trip): bool {
        return $trip->driver?->id === $user->driver?->id;
    }
}
