<?php

namespace App\Policies;

use App\Models\User;

class LeaderboardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        if($user->can('access admin panel'))
        {
            return true;
        }
    }
}
