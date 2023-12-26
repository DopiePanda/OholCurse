<?php

namespace App\Policies;

use App\Models\ProfileRestriction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProfileRestrictionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->can('can view profile restrictions'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProfileRestriction $profileRestriction): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->can('can view profile restrictions'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProfileRestriction $profileRestriction): bool
    {
        if($user->can('can view profile restrictions'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProfileRestriction $profileRestriction): bool
    {
        if($user->can('can view profile restrictions'))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProfileRestriction $profileRestriction): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProfileRestriction $profileRestriction): bool
    {
        //
    }
}
