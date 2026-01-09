<?php

namespace App\Services;

use App\Models\Client;
use App\Models\ClientPriceOverride;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ClientPriceOverrideService
{
    public function getClientOverrides(int $clientId): Collection
    {
        return ClientPriceOverride::where('client_id', $clientId)
            ->with(['wasteType', 'createdBy'])
            ->latest()
            ->get();
    }

    public function getActiveOverrides(int $clientId, ?Carbon $date = null): Collection
    {
        $date = $date ?? now();

        return ClientPriceOverride::where('client_id', $clientId)
            ->where('effective_from', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $date);
            })
            ->with('wasteType')
            ->get();
    }

    public function findOverride(int $id): ?ClientPriceOverride
    {
        return ClientPriceOverride::with(['client', 'wasteType', 'createdBy'])->find($id);
    }

    public function createOverride(array $data): ClientPriceOverride
    {
        return ClientPriceOverride::create([
            'client_id' => $data['client_id'],
            'waste_type_id' => $data['waste_type_id'],
            'custom_price' => $data['custom_price'],
            'currency' => $data['currency'] ?? 'PLN',
            'tax_rate' => $data['tax_rate'] ?? 0.23,
            'unit_type' => $data['unit_type'] ?? 'per_pickup',
            'effective_from' => $data['effective_from'],
            'effective_to' => $data['effective_to'] ?? null,
            'notes' => $data['notes'] ?? null,
            'created_by_user_id' => $data['created_by_user_id'] ?? auth()->id(),
        ]);
    }

    public function updateOverride(int $id, array $data): ?ClientPriceOverride
    {
        $override = ClientPriceOverride::find($id);

        if (!$override) {
            return null;
        }

        $override->update([
            'custom_price' => $data['custom_price'] ?? $override->custom_price,
            'currency' => $data['currency'] ?? $override->currency,
            'tax_rate' => $data['tax_rate'] ?? $override->tax_rate,
            'unit_type' => $data['unit_type'] ?? $override->unit_type,
            'effective_from' => $data['effective_from'] ?? $override->effective_from,
            'effective_to' => $data['effective_to'] ?? $override->effective_to,
            'notes' => $data['notes'] ?? $override->notes,
        ]);

        return $override->fresh(['wasteType', 'createdBy']);
    }

    public function deleteOverride(int $id): bool
    {
        $override = ClientPriceOverride::find($id);

        if (!$override) {
            return false;
        }

        return $override->delete();
    }

    public function getEffectivePrice(int $clientId, int $wasteTypeId, ?Carbon $date = null): ?ClientPriceOverride
    {
        $date = $date ?? now();

        return ClientPriceOverride::where('client_id', $clientId)
            ->where('waste_type_id', $wasteTypeId)
            ->where('effective_from', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereNull('effective_to')
                    ->orWhere('effective_to', '>=', $date);
            })
            ->orderBy('effective_from', 'desc')
            ->first();
    }

    public function expireOverride(int $id): ?ClientPriceOverride
    {
        $override = ClientPriceOverride::find($id);

        if (!$override) {
            return null;
        }

        $override->update([
            'effective_to' => now(),
        ]);

        return $override->fresh();
    }

    public function hasConflict(int $clientId, int $wasteTypeId, Carbon $effectiveFrom, ?Carbon $effectiveTo = null, ?int $excludeId = null): bool
    {
        $query = ClientPriceOverride::where('client_id', $clientId)
            ->where('waste_type_id', $wasteTypeId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $query->where(function ($q) use ($effectiveFrom, $effectiveTo) {
            $q->where(function ($subQ) use ($effectiveFrom) {
                $subQ->where('effective_from', '<=', $effectiveFrom)
                    ->where(function ($dateQ) use ($effectiveFrom) {
                        $dateQ->whereNull('effective_to')
                            ->orWhere('effective_to', '>=', $effectiveFrom);
                    });
            });

            if ($effectiveTo) {
                $q->orWhere(function ($subQ) use ($effectiveFrom, $effectiveTo) {
                    $subQ->whereBetween('effective_from', [$effectiveFrom, $effectiveTo]);
                });
            }
        });

        return $query->exists();
    }
}