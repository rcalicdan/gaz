<?php

namespace App\Policies;

use App\Models\Route;
use App\Models\User;

class RoutePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, Route $route): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Route $route): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, Route $route): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, Route $route): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, Route $route): bool
    {
        return $user->canDelete();
    }
}