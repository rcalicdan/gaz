<?php

namespace App\Models;

use App\Services\KpoPdfService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

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
        'pdf_path',
        'pdf_version',
        'pdf_generated_at',
        'is_emailed',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'is_emailed' => 'boolean',
            'pdf_generated_at' => 'datetime',
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

    public function needsRegeneration(): bool
    {
        if (!$this->pdf_path) {
            return true;
        }

        if (!Storage::exists($this->pdf_path)) {
            return true;
        }

        if ($this->pdf_generated_at && $this->updated_at > $this->pdf_generated_at) {
            return true;
        }

        return false;
    }

    public function regeneratePdf(): self
    {
        app(KpoPdfService::class)->generateKpoDocument($this);
        
        return $this->fresh();
    }

    public function ensurePdfIsReady(): self
    {
        if ($this->needsRegeneration()) {
            $this->regeneratePdf();
        }

        return $this;
    }

    public function deletePdfFile(): bool
    {
        if ($this->pdf_path && Storage::exists($this->pdf_path)) {
            return Storage::delete($this->pdf_path);
        }

        return false;
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }

        return Storage::url($this->pdf_path);
    }

    public function hasPdf(): bool
    {
        return $this->pdf_path && Storage::exists($this->pdf_path);
    }

    public function getPdfSize(): ?int
    {
        if ($this->hasPdf()) {
            return Storage::size($this->pdf_path);
        }

        return null;
    }

    public function getPdfSizeForHumans(): ?string
    {
        $size = $this->getPdfSize();
        
        if (!$size) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $power = floor(log($size, 1024));
        
        return round($size / pow(1024, $power), 2) . ' ' . $units[$power];
    }

    public function getPdfLastModified(): ?int
    {
        if ($this->hasPdf()) {
            return Storage::lastModified($this->pdf_path);
        }

        return null;
    }

    public function scopeWithValidPdf($query)
    {
        return $query->whereNotNull('pdf_path')
                    ->whereNotNull('pdf_generated_at');
    }

    public function scopeNeedsRegeneration($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('pdf_path')
              ->orWhereNull('pdf_generated_at')
              ->orWhereColumn('updated_at', '>', 'pdf_generated_at');
        });
    }
}