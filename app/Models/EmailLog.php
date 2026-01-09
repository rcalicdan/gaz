<?php

namespace App\Models;

use App\Enums\DocumentType;
use App\Enums\EmailStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_type',
        'document_id',
        'recipient_email',
        'sent_at',
        'status',
        'error_message',
        'sent_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'document_type' => DocumentType::class,
            'sent_at' => 'datetime',
            'status' => EmailStatus::class,
        ];
    }

    public function document(): MorphTo
    {
        return $this->morphTo();
    }

    public function sentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by_user_id');
    }
}