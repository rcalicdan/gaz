<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteOptimization extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'optimization_date',
        'optimization_result',
        'pickup_sequence',
        'total_distance',
        'total_time',
        'is_manual_edit',
        'manual_modifications',
        'requires_optimization'
    ];

    protected $casts = [
        'optimization_date' => 'date',
        'optimization_result' => 'array',
        'pickup_sequence' => 'array',
        'manual_modifications' => 'array',
        'is_manual_edit' => 'boolean',
        'requires_optimization' => 'boolean'
    ];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }
}