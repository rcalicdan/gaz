<?php

namespace App\Observers;

use App\Enums\UserRole;
use App\Models\Driver;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role === UserRole::DRIVER) {
            Driver::create(['user_id' => $user->id]);
        }
    }

    public function updated(User $user): void
    {
        $wasDriver = $user->getOriginal('role') === UserRole::DRIVER->value;
        $isDriver = $user->role === UserRole::DRIVER;

        if (!$wasDriver && $isDriver) {
            Driver::create(['user_id' => $user->id]);
        } elseif ($wasDriver && !$isDriver) {
            Driver::where('user_id', $user->id)->delete();
        }
    }
}
