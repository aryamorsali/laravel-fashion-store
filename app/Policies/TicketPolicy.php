<?php

namespace App\Policies;

use App\Models\Ticket\Ticket;
use App\Models\User;

class TicketPolicy
{
    /**
     * Create a new policy instance.
     */
    public function show(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id;
    }

    public function answer(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id;
    }

    public function change(User $user, Ticket $ticket): bool
    {
        return $ticket->user_id === $user->id;
    }
}
