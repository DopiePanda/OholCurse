<?php

namespace App\Policies;

use App\Models\User;

class BadgePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->can('view badges'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Yumlog $yumlog): bool
    {
        if($user->can('view badges'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->can('create badges'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GameLeaderboard $leaderboard): bool
    {
        if($user->can('edit badges'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GameLeaderboard $leaderboard): bool
    {
        if($user->can('delete badges'))
        {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Yumlog $yumlog): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Yumlog $yumlog): bool
    {
        return false;
    }
}
