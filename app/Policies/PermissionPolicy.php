<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
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
        if ($user->can('view all permissions')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('create permissions')) {
            return true;
        }
    }

    public function update(User $user)
    {
        if ($user->can('edit permissions')) {
            return true;
        }
    }

    public function view(User $user)
    {
        if ($user->can('view permissions')) {
            return true;
        }
    }

    public function delete(User $user)
    {
        if ($user->can('delete permissions')) {
            return true;
        }
    }

    public function deleteAny(User $user)
    {
        if ($user->can('delete permissions')) {
            return true;
        }
    }
}
