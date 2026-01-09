<?php

namespace App\Policies;

use App\Models\EmailLog;
use App\Models\User;

class EmailLogPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, EmailLog $emailLog): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, EmailLog $emailLog): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, EmailLog $emailLog): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, EmailLog $emailLog): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, EmailLog $emailLog): bool
    {
        return $user->canDelete();
    }
}