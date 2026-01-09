<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PrintLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_id',
        'printed_at',
        'printed_by_user_id',
        'copies',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => DocumentType::class,
            'printed_at' => 'datetime',
        ];
    }

    public function document(): MorphTo
    {
        return $this->morphTo();
    }

    public function printedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'printed_by_user_id');
    }
}