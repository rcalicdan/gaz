<?php

namespace App\Observers;

use App\Models\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ClientObserver
{
    protected array $addressFields = [
        'street_name',
        'street_number',
        'zip_code',
        'city',
        'province'
    ];

    public function created(Client $client): void
    {
        $this->handleGeocoding($client, 'created');
    }

    public function updating(Client $client): void
    {
        if ($client->isDirty($this->addressFields)) {
            $client->latitude = null;
            $client->longitude = null;
        }
    }

    public function updated(Client $client): void
    {
        $hasAddressChanges = collect($this->addressFields)
            ->some(fn($field) => $client->wasChanged($field));

        if ($hasAddressChanges) {
            $this->handleGeocoding($client, 'updated');
        }
    }

    public function deleted(Client $client): void
    {
        $this->clearGeocodingCache($client);
    }

    private function handleGeocoding(Client $client, string $action): void
    {
        if (!$this->hasMinimumAddressInfo($client)) {
            Log::info("Skipping geocoding - insufficient address data", [
                'client_id' => $client->id,
                'action' => $action,
                'address' => $client->full_address
            ]);
            return;
        }

        defer(function () use ($client, $action) {
            $this->performDeferredGeocoding($client, $action);
        });

        Log::info("Geocoding queued for deferred processing", [
            'client_id' => $client->id,
            'action' => $action
        ]);
    }

    private function performDeferredGeocoding(Client $client, string $action): void
    {
        try {
            $freshClient = Client::find($client->id);

            if (!$freshClient) {
                return;
            }

            if ($freshClient->hasCoordinates()) {
                return;
            }

            $result = $freshClient->geocodeAddress();

            if ($result) {
                $freshClient->saveQuietly();

                Log::info("Deferred geocoding completed", [
                    'client_id' => $freshClient->id,
                    'coordinates' => [
                        'lat' => $freshClient->latitude,
                        'lng' => $freshClient->longitude
                    ]
                ]);
            } else {
                Log::warning("Deferred geocoding returned no results", [
                    'client_id' => $freshClient->id,
                    'address' => $freshClient->full_address
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Exception during deferred geocoding", [
                'client_id' => $client->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function hasMinimumAddressInfo(Client $client): bool
    {
        return !empty($client->city) && !empty($client->street_name);
    }

    private function clearGeocodingCache(Client $client): void
    {
        if ($client->full_address) {
            $cacheKey = 'geocode_client_' . $client->id . '_' . md5($client->full_address);

            Cache::forget($cacheKey);

            Log::info("Cleared geocoding cache", [
                'client_id' => $client->id
            ]);
        }
    }
}