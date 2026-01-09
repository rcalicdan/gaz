<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PickupBox extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_id',
        'box_number',
        'note',
    ];

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Pickup::class);
    }
}