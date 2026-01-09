<?php

namespace App\Policies;

use App\Models\PickupBox;
use App\Models\User;

class PickupBoxPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, PickupBox $pickupBox): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, PickupBox $pickupBox): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, PickupBox $pickupBox): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, PickupBox $pickupBox): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, PickupBox $pickupBox): bool
    {
        return $user->canDelete();
    }
}