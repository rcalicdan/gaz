<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PriceListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'price_list_id',
        'waste_type_id',
        'base_price',
        'currency',
        'tax_rate',
        'unit_type',
        'min_quantity',
        'max_quantity',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'tax_rate' => 'decimal:4',
            'min_quantity' => 'decimal:2',
            'max_quantity' => 'decimal:2',
        ];
    }

    public function priceList(): BelongsTo
    {
        return $this->belongsTo(PriceList::class);
    }

    public function wasteType(): BelongsTo
    {
        return $this->belongsTo(WasteType::class);
    }
}