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
        'contract_number',
        'contract_signed_date',
        'registered_street_name',
        'registered_street_number',
        'registered_city',
        'registered_zip_code',
        'registered_province',
        'premises_street_name',
        'premises_street_number',
        'premises_city',
        'premises_zip_code',
        'premises_province',
        'premises_latitude',
        'premises_longitude',
        'contact_person',
        'email',
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
            'premises_latitude' => 'decimal:8',
            'premises_longitude' => 'decimal:8',
            'price_rate' => 'decimal:2',
            'tax_rate' => 'integer',
            'auto_invoice' => 'boolean',
            'auto_kpo' => 'boolean',
            'last_contact_date' => 'datetime',
            'last_pickup_date' => 'datetime',
            'contract_signed_date' => 'date',
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

    public function phoneNumbers(): HasMany
    {
        return $this->hasMany(ClientPhoneNumber::class);
    }

    public function primaryPhoneNumber()
    {
        return $this->hasOne(ClientPhoneNumber::class)->where('is_primary', true);
    }

    public function getPrimaryPhoneAttribute(): ?string
    {
        return $this->primaryPhoneNumber?->phone_number;
    }

    public function scopeNeedsContact($query, $days = 30)
    {
        return $query->where('last_contact_date', '<', now()->subDays($days))
            ->orWhereNull('last_contact_date');
    }

    public function scopeByContractNumber($query, string $contractNumber)
    {
        return $query->where('contract_number', $contractNumber);
    }

    public function scopeWithContract($query)
    {
        return $query->whereNotNull('contract_number')
            ->whereNotNull('contract_signed_date');
    }

    public function scopeWithoutContract($query)
    {
        return $query->whereNull('contract_number')
            ->orWhereNull('contract_signed_date');
    }

    public function scopeContractSignedBetween($query, $startDate, $endDate)
    {
        return $query->whereBetween('contract_signed_date', [$startDate, $endDate]);
    }

    public function scopeContractSignedBefore($query, $date)
    {
        return $query->where('contract_signed_date', '<', $date);
    }

    public function scopeContractSignedAfter($query, $date)
    {
        return $query->where('contract_signed_date', '>', $date);
    }

    public function hasContract(): bool
    {
        return !is_null($this->contract_number) && !is_null($this->contract_signed_date);
    }

    public function getContractAgeInDaysAttribute(): ?int
    {
        if (!$this->contract_signed_date) {
            return null;
        }
        return $this->contract_signed_date->diffInDays(now());
    }

    public function getContractAgeInMonthsAttribute(): ?int
    {
        if (!$this->contract_signed_date) {
            return null;
        }
        return $this->contract_signed_date->diffInMonths(now());
    }

    public function getContractAgeInYearsAttribute(): ?float
    {
        if (!$this->contract_signed_date) {
            return null;
        }
        return round($this->contract_signed_date->diffInYears(now(), true), 1);
    }

    public function getContractAgeFormattedAttribute(): ?string
    {
        if (!$this->contract_signed_date) {
            return null;
        }

        $years = $this->contract_signed_date->diffInYears(now());
        $months = $this->contract_signed_date->diffInMonths(now()) % 12;
        $days = $this->contract_signed_date->copy()->addMonths($years * 12 + $months)->diffInDays(now());

        $parts = [];

        if ($years > 0) {
            $parts[] = $years . ' year' . ($years > 1 ? 's' : '');
        }

        if ($months > 0) {
            $parts[] = $months . ' month' . ($months > 1 ? 's' : '');
        }

        if ($days > 0 && $years === 0) {
            $parts[] = $days . ' day' . ($days > 1 ? 's' : '');
        }

        return !empty($parts) ? implode(', ', $parts) : 'Today';
    }

    public function getContractSignedDateFormattedAttribute(): ?string
    {
        if (!$this->contract_signed_date) {
            return null;
        }
        return $this->contract_signed_date->format('d.m.Y');
    }

    public function getFullAddressAttribute(): string
    {
        $streetParts = array_filter([
            $this->premises_street_name ?? $this->registered_street_name,
            $this->premises_street_number ?? $this->registered_street_number,
        ]);
        
        return trim(implode(', ', array_filter([
            trim(implode(' ', $streetParts)),
            $this->premises_zip_code ?? $this->registered_zip_code,
            $this->premises_city ?? $this->registered_city,
            $this->premises_province ?? $this->registered_province,
        ])));
    }

    public function getCoordinatesAttribute(): ?array
    {
        if ($this->premises_latitude && $this->premises_longitude) {
            return [(float) $this->premises_latitude, (float) $this->premises_longitude];
        }
        return null;
    }

    public function getVroomCoordinatesAttribute(): ?array
    {
        if ($this->premises_latitude && $this->premises_longitude) {
            return [(float) $this->premises_longitude, (float) $this->premises_latitude];
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
                    'premises_latitude' => $coordinates['lat'],
                    'premises_longitude' => $coordinates['lng']
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
        return !is_null($this->premises_latitude)
            && !is_null($this->premises_longitude)
            && $this->premises_latitude != 0
            && $this->premises_longitude != 0;
    }

    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('premises_latitude')
            ->whereNotNull('premises_longitude')
            ->where('premises_latitude', '!=', 0)
            ->where('premises_longitude', '!=', 0);
    }

    public function scopeWithoutCoordinates($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('premises_latitude')
                ->orWhereNull('premises_longitude')
                ->orWhere('premises_latitude', 0)
                ->orWhere('premises_longitude', 0);
        });
    }
}