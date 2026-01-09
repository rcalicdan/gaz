<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WasteType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'default_waste_type_id');
    }

    public function pickups(): HasMany
    {
        return $this->hasMany(Pickup::class, 'waste_type_id');
    }
}