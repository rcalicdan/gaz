<?php

namespace App\Services;

use App\Models\Pickup;
use App\Enums\PickupStatus;
use Illuminate\Support\Facades\DB;

class PickupService
{
    public function createPickup(array $data): Pickup
    {
        return DB::transaction(function () use ($data) {
            $pickup = Pickup::create([
                'client_id' => $data['client_id'],
                'assigned_driver_id' => $data['assigned_driver_id'] ?? null,
                'scheduled_date' => $data['scheduled_date'],
                'status' => $data['status'] ?? PickupStatus::SCHEDULED,
                'waste_type_id' => $data['waste_type_id'],
                'waste_quantity' => $data['waste_quantity'] ?? null,
                'applied_price_rate' => $data['applied_price_rate'] ?? null,
                'driver_note' => $data['driver_note'] ?? null,
                'certificate_number' => $data['certificate_number'] ?? null,
                'sequence_order' => $data['sequence_order'] ?? null,
            ]);

            if (isset($data['boxes']) && is_array($data['boxes'])) {
                foreach ($data['boxes'] as $box) {
                    if (!empty($box['box_number'])) {
                        $pickup->boxes()->create([
                            'box_number' => $box['box_number'],
                            'note' => $box['note'] ?? null,
                        ]);
                    }
                }
            }

            return $pickup->fresh(['client', 'driver', 'wasteType', 'boxes']);
        });
    }

    public function updatePickup(int $id, array $data): Pickup
    {
        return DB::transaction(function () use ($id, $data) {
            $pickup = Pickup::findOrFail($id);
            
            $pickup->update([
                'client_id' => $data['client_id'],
                'assigned_driver_id' => $data['assigned_driver_id'] ?? null,
                'scheduled_date' => $data['scheduled_date'],
                'status' => $data['status'] ?? PickupStatus::SCHEDULED,
                'waste_type_id' => $data['waste_type_id'],
                'waste_quantity' => $data['waste_quantity'] ?? null,
                'applied_price_rate' => $data['applied_price_rate'] ?? null,
                'driver_note' => $data['driver_note'] ?? null,
                'certificate_number' => $data['certificate_number'] ?? null,
                'actual_pickup_time' => $data['actual_pickup_time'] ?? null,
                'sequence_order' => $data['sequence_order'] ?? null,
            ]);

            if (isset($data['boxes'])) {
                $pickup->boxes()->delete();
                foreach ($data['boxes'] as $box) {
                    if (!empty($box['box_number'])) {
                        $pickup->boxes()->create([
                            'box_number' => $box['box_number'],
                            'note' => $box['note'] ?? null,
                        ]);
                    }
                }
            }

            return $pickup->fresh(['client', 'driver', 'wasteType', 'boxes']);
        });
    }

    public function deletePickup(int $id): bool
    {
        $pickup = Pickup::findOrFail($id);
        return $pickup->delete();
    }

    public function updateStatus(int $id, PickupStatus $status): Pickup
    {
        $pickup = Pickup::findOrFail($id);
        
        if (!$pickup->status->canTransitionTo($status)) {
            throw new \Exception("Cannot transition from {$pickup->status->value} to {$status->value}");
        }

        $pickup->update(['status' => $status]);

        if ($status === PickupStatus::COMPLETED && !$pickup->actual_pickup_time) {
            $pickup->update(['actual_pickup_time' => now()]);
        }

        return $pickup->fresh();
    }

    public function completePickup(int $id, array $data): Pickup
    {
        return DB::transaction(function () use ($id, $data) {
            $pickup = Pickup::findOrFail($id);
            
            $pickup->update([
                'status' => PickupStatus::COMPLETED,
                'actual_pickup_time' => $data['actual_pickup_time'] ?? now(),
                'waste_quantity' => $data['waste_quantity'],
                'driver_note' => $data['driver_note'] ?? null,
                'certificate_number' => $data['certificate_number'] ?? null,
            ]);

            return $pickup->fresh();
        });
    }
}