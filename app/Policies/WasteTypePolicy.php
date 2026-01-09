<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WasteType;

class WasteTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, WasteType $wasteType): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, WasteType $wasteType): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, WasteType $wasteType): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, WasteType $wasteType): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, WasteType $wasteType): bool
    {
        return $user->canDelete();
    }
}