<?php

namespace App\Policies;

use App\Models\Market\Address;
use App\Models\User;

class AddressPolicy
{
    /**
     * Create a new policy instance.
     */
    public function update(User $user, Address $address): bool
    {
        return $address->user_id === $user->id;
    }
}
