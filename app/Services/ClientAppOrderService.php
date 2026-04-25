<?php

namespace App\Services;

use App\Models\Pickup;
use App\Models\Client;
use App\Models\WasteType;
use App\Enums\PickupStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Collection;

class ClientAppOrderService
{
    public function getWasteTypes(): Collection
    {
        return WasteType::orderBy('name')->get();
    }

    public function getClientOrders(int $clientId)
    {
        return Pickup::with(['wasteType', 'driver.user', 'boxes'])
            ->where('client_id', $clientId)
            ->orderBy('scheduled_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getClientOrder(int $clientId, int $orderId): Pickup
    {
        $pickup = Pickup::with(['wasteType', 'driver.user', 'boxes'])
            ->where('client_id', $clientId)
            ->where('id', $orderId)
            ->first();

        if (!$pickup) {
            throw ValidationException::withMessages([
                'order' => ['Order not found or access denied.'],
            ]);
        }

        return $pickup;
    }

    public function createOrder(Client $client, array $data): Pickup
    {
        return DB::transaction(function () use ($client, $data) {
            $pickup = Pickup::create([
                'client_id' => $client->id,
                'waste_type_id' => $data['waste_type_id'],
                'scheduled_date' => $data['scheduled_date'],
                'status' => PickupStatus::SCHEDULED,
                'applied_price_rate' => $client->price_rate ?? null,
                'driver_note' => $data['driver_note'] ?? null,
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

            return $pickup->fresh(['wasteType', 'boxes']);
        });
    }

    public function cancelOrder(int $clientId, int $orderId): Pickup
    {
        $pickup = $this->getClientOrder($clientId, $orderId);

        if ($pickup->status !== PickupStatus::SCHEDULED) {
            throw ValidationException::withMessages([
                'order' => ['Only scheduled orders can be cancelled.'],
            ]);
        }

        $pickup->update([
            'status' => PickupStatus::CANCELLED
        ]);

        return $pickup->fresh(['wasteType', 'driver.user', 'boxes']);
    }
}