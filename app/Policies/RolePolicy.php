<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        if ($user->can('view all roles')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('create roles')) {
            return true;
        }
    }

    public function update(User $user)
    {
        if ($user->can('edit roles')) {
            return true;
        }
    }

    public function view(User $user)
    {
        if ($user->can('view roles')) {
            return true;
        }
    }

    public function delete(User $user)
    {
        if ($user->can('delete roles')) {
            return true;
        }
    }

    public function deleteAny(User $user)
    {
        if ($user->can('delete roles')) {
            return true;
        }
    }
}
