<?php

namespace App\Models;

use App\Enums\PickupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pickup extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'route_id',
        'assigned_driver_id',
        'sequence_order',
        'scheduled_date',
        'actual_pickup_time',
        'status',
        'waste_quantity',
        'waste_type_id',
        'driver_note',
        'applied_price_rate',
        'certificate_number',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_date' => 'date',
            'actual_pickup_time' => 'datetime',
            'status' => PickupStatus::class,
            'waste_quantity' => 'decimal:2',
            'applied_price_rate' => 'decimal:2',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class, 'assigned_driver_id');
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }

    public function boxes(): HasMany
    {
        return $this->hasMany(PickupBox::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function kpoDocument(): HasOne
    {
        return $this->hasOne(KpoDocument::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status->label();
    }

    public function getDriverNameAttribute(): string
    {
        return $this->driver?->user?->full_name ?? '';
    }

    public function getWasteTypeNameAttribute(): string
    {
        return $this->wasteType?->name ?? '';
    }

    public function getVroomCoordinatesAttribute(): ?array
    {
        if ($this->client && $this->client->longitude && $this->client->latitude) {
            return [
                (float) $this->client->longitude,
                (float) $this->client->latitude
            ];
        }
        return null;
    }
}
