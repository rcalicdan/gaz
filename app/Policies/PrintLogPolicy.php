<?php

namespace App\Policies;

use App\Models\PrintLog;
use App\Models\User;

class PrintLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, PrintLog $printLog): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, PrintLog $printLog): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, PrintLog $printLog): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, PrintLog $printLog): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, PrintLog $printLog): bool
    {
        return $user->canDelete();
    }
}