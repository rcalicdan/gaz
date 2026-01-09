<?php

namespace App\Policies;

use App\Models\Pickup;
use App\Models\User;

class PickupPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, Pickup $pickup): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Pickup $pickup): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, Pickup $pickup): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, Pickup $pickup): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, Pickup $pickup): bool
    {
        return $user->canDelete();
    }
}