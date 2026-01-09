<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class KpoDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_id',
        'client_id',
        'kpo_number',
        'waste_code',
        'quantity',
        'additional_notes',
        'pdf_url',
        'is_emailed',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'is_emailed' => 'boolean',
        ];
    }

    public function pickup(): BelongsTo
    {
        return $this->belongsTo(Pickup::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function emailLogs(): MorphMany
    {
        return $this->morphMany(EmailLog::class, 'document');
    }

    public function printLogs(): MorphMany
    {
        return $this->morphMany(PrintLog::class, 'document');
    }
}