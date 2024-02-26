<?php

namespace App\Policies;

use App\Models\User;

class PhexHashPolicy
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
        if ($user->can('view all phex hashes')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('create phex hashes')) {
            return true;
        }
    }

    public function update(User $user)
    {
        if ($user->can('edit phex hashes')) {
            return true;
        }
    }

    public function view(User $user)
    {
        if ($user->can('view phex hashes')) {
            return true;
        }
    }

    public function delete(User $user)
    {
        if ($user->can('delete phex hashes')) {
            return true;
        }
    }

    public function deleteAny(User $user)
    {
        if ($user->can('delete phex hashes')) {
            return true;
        }
    }
}
