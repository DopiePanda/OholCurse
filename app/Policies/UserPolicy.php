<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        if ($user->can('view all users')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('create users')) {
            return true;
        }
    }

    public function update(User $user)
    {
        if ($user->can('edit users')) {
            return true;
        }
    }

    public function view(User $user)
    {
        if ($user->can('view users')) {
            return true;
        }
    }

    public function delete(User $user)
    {
        if ($user->can('delete users')) {
            return true;
        }
    }

    public function deleteAny(User $user)
    {
        if ($user->can('delete users')) {
            return true;
        }
    }
}
