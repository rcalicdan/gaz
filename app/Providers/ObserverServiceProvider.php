<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Pickup;
use App\Models\Reminder;
use App\Models\User;
use App\Observers\ClientObserver;
use App\Observers\PickupObserver;
use App\Observers\ReminderObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        User::observe(UserObserver::class);
        Client::observe(ClientObserver::class);
        Pickup::observe(PickupObserver::class);
        Reminder::observe(ReminderObserver::class);
    }
}
