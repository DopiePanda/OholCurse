<?php

namespace App\Broadcasting;

use App\Models\User;
use App\Models\UserConversation;
use Auth;

class Message
{

    /**
     * Create a new channel instance.
     */
    public function __construct()
    {

    }

    /**
     * Authenticate the user's access to the channel.
     */
    public function join(User $user, UserConversation $conversation): array|bool
    {
        return Auth::check() && (int) $user->id == Auth::id();
    }
}
