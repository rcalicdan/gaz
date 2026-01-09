<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canManage();
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return $user->canManage();
    }

    public function create(User $user): bool
    {
        return $user->canManage();
    }

    public function update(User $user, Invoice $invoice): bool
    {
        return $user->canManage();
    }

    public function delete(User $user, Invoice $invoice): bool
    {
        return $user->canDelete();
    }

    public function restore(User $user, Invoice $invoice): bool
    {
        return $user->canDelete();
    }

    public function forceDelete(User $user, Invoice $invoice): bool
    {
        return $user->canDelete();
    }
}