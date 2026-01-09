<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, Client $client): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Client $client): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, Client $client): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, Client $client): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, Client $client): bool
    {
        return $user->canDelete();
    }
}