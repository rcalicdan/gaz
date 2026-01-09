<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPriceOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'waste_type_id',
        'custom_price',
        'currency',
        'tax_rate',
        'unit_type',
        'effective_from',
        'effective_to',
        'notes',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'custom_price' => 'decimal:2',
            'tax_rate' => 'decimal:4',
            'effective_from' => 'date',
            'effective_to' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}