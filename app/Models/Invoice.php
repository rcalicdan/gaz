<?php

namespace App\Models;

use App\Enums\KsefStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'pickup_id',
        'client_id',
        'invoice_number',
        'issue_date',
        'due_date',
        'net_amount',
        'vat_amount',
        'gross_amount',
        'description',
        'ksef_status',
        'ksef_reference_number',
        'ksef_xml_content',
        'pdf_url',
        'is_emailed',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'due_date' => 'date',
            'net_amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'gross_amount' => 'decimal:2',
            'ksef_status' => KsefStatus::class,
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