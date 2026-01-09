<?php

namespace App\Policies;

use App\Models\KpoDocument;
use App\Models\User;

class KpoDocumentPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, KpoDocument $kpoDocument): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, KpoDocument $kpoDocument): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, KpoDocument $kpoDocument): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, KpoDocument $kpoDocument): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, KpoDocument $kpoDocument): bool
    {
        return $user->canDelete();
    }
}