<?php

namespace App\Observers;

use App\Enums\PickupFrequency;
use App\Enums\PickupStatus;
use App\Models\Pickup;
use Illuminate\Support\Facades\Log;

class PickupObserver
{
    public function saved(Pickup $pickup): void
    {
        if ($pickup->isDirty('status') && $pickup->status === PickupStatus::COMPLETED) {
            
            $client = $pickup->client;

            $client->update([
                'last_contact_date' => now()
            ]);

            $this->handleAutoScheduling($pickup, $client);
        }
    }

    private function handleAutoScheduling(Pickup $previousPickup, $client): void
    {
        if (!$client->pickup_frequency || $client->pickup_frequency === PickupFrequency::ON_DEMAND) {
            return;
        }

        $daysToAdd = $client->pickup_frequency->days();
        
        if (!$daysToAdd) {
            return; 
        }

        $nextDate = now()->addDays($daysToAdd);

        $exists = Pickup::where('client_id', $client->id)
            ->where('status', PickupStatus::SCHEDULED)
            ->whereDate('scheduled_date', '>=', now()->format('Y-m-d'))
            ->exists();

        if (!$exists) {
            try {
                Pickup::create([
                    'client_id' => $client->id,
                    'waste_type_id' => $previousPickup->waste_type_id, 
                    'driver_id' => $previousPickup->driver_id, 
                    'route_id' => $previousPickup->route_id,
                    'scheduled_date' => $nextDate,
                    'status' => PickupStatus::SCHEDULED,
                    'applied_price_rate' => $client->price_rate ?? $previousPickup->applied_price_rate,
                    'driver_note' => 'Auto-generated: ' . $client->pickup_frequency->label(),
                ]);

                Log::info("Auto-scheduled next pickup for Client #{$client->id} on {$nextDate->format('Y-m-d')}");

            } catch (\Exception $e) {
                Log::error("Failed to auto-schedule pickup for Client #{$client->id}: " . $e->getMessage());
            }
        }
    }
}