<?php

namespace App\Observers;

use App\Models\Reminder;

class ReminderObserver
{
    public function saved(Reminder $reminder): void
    {
        if ($reminder->isDirty('status') && $reminder->status === 'completed') {
            $reminder->client()->update([
                'last_contact_date' => now()
            ]);
        }
    }
}