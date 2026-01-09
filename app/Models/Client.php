<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'vat_id',
        'street_name',
        'street_number',
        'city',
        'zip_code',
        'province',
        'latitude',
        'longitude',
        'contact_person',
        'email',
        'phone_number',
        'brand_category',
        'default_waste_type_id',
        'price_list_id',
        'pickup_frequency_days',
        'price_rate',
        'currency',
        'tax_rate',
        'auto_invoice',
        'auto_kpo',
        'last_contact_date',
        'last_pickup_date',
        'pickup_frequency',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
            'price_rate' => 'decimal:2',
            'tax_rate' => 'integer',
            'auto_invoice' => 'boolean',
            'auto_kpo' => 'boolean',
            'last_contact_date' => 'datetime',
            'last_pickup_date' => 'datetime',
            'pickup_frequency' => \App\Enums\PickupFrequency::class,
        ];
    }

    public function defaultWasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class, 'default_waste_type_id');
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function priceOverrides(): HasMany
    {
        return $this->hasMany(ClientPriceOverride::class);
    }

    public function pickups(): HasMany
    {
        return $this->hasMany(Pickup::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function kpoDocuments(): HasMany
    {
        return $this->hasMany(KpoDocument::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    public function scopeNeedsContact($query, $days = 30)
    {
        return $query->where('last_contact_date', '<', now()->subDays($days))
            ->orWhereNull('last_contact_date');
    }

    public function getFullAddressAttribute(): string
    {
        return trim(implode(', ', array_filter([
            trim($this->street_name . ' ' . $this->street_number),
            $this->zip_code,
            $this->city,
            $this->province,
        ])));
    }

    public function getCoordinatesAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [(float) $this->latitude, (float) $this->longitude];
        }
        return null;
    }

    public function getVroomCoordinatesAttribute(): ?array
    {
        if ($this->latitude && $this->longitude) {
            return [(float) $this->longitude, (float) $this->latitude];
        }
        return null;
    }

    public function geocodeAddress(): bool
    {
        $address = $this->full_address;

        if (empty($address) || strlen($address) < 5) {
            Log::warning('Cannot geocode empty or short address for client', ['client_id' => $this->id]);
            return false;
        }

        $cacheKey = 'geocode_client_' . $this->id . '_' . md5($address);

        try {
            $coordinates = Cache::remember($cacheKey, 2592000, function () use ($address) {
                return $this->performGeocoding($address);
            });

            if ($coordinates) {
                $this->updateQuietly([
                    'latitude' => $coordinates['lat'],
                    'longitude' => $coordinates['lng']
                ]);

                Log::info('Successfully geocoded address', [
                    'client_id' => $this->id,
                    'address' => $address,
                    'coordinates' => $coordinates
                ]);

                return true;
            }
        } catch (\Exception $e) {
            Log::error('Geocoding failed for client', [
                'client_id' => $this->id,
                'address' => $address,
                'error' => $e->getMessage()
            ]);
        }

        return false;
    }

    private function performGeocoding(string $address): ?array
    {
        try {
            $response = Http::timeout(10)
                ->retry(2, 1000)
                ->withHeaders([
                    'User-Agent' => config('app.name', 'LaravelApp') . ' Geocoding/1.0'
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
                    'format' => 'json',
                    'q' => $address,
                    'limit' => 1,
                    'addressdetails' => 1,
                ]);

            if ($response->successful()) {
                $results = $response->json();

                if (!empty($results) && isset($results[0]['lat'], $results[0]['lon'])) {
                    return [
                        'lat' => (float) $results[0]['lat'],
                        'lng' => (float) $results[0]['lon'],
                        'display_name' => $results[0]['display_name'] ?? null
                    ];
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }

        return null;
    }

    public function forceGeocode(): bool
    {
        $address = $this->full_address;
        $cacheKey = 'geocode_client_' . $this->id . '_' . md5($address);

        Cache::forget($cacheKey);

        return $this->geocodeAddress();
    }

    public function hasCoordinates(): bool
    {
        return !is_null($this->latitude)
            && !is_null($this->longitude)
            && $this->latitude != 0
            && $this->longitude != 0;
    }

    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('latitude', '!=', 0)
            ->where('longitude', '!=', 0);
    }

    public function scopeWithoutCoordinates($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('latitude')
                ->orWhereNull('longitude')
                ->orWhere('latitude', 0)
                ->orWhere('longitude', 0);
        });
    }
}
