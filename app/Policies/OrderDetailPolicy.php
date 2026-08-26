<?php

namespace App\Policies;

use App\Models\Market\Order;
use App\Models\User;

class OrderDetailPolicy
{
    /**
     * Create a new policy instance.
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id;
    }
}
