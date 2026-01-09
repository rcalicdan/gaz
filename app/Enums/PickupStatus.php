<?php

namespace App\Enums;

enum PickupStatus: string
{
    case SCHEDULED = 'scheduled';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case SKIPPED = 'skipped';
    case CANCELLED = 'cancelled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($status) => [
            $status->value => $status->label()
        ])->toArray();
    }

    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => __('Scheduled'),
            self::IN_PROGRESS => __('In Progress'),
            self::COMPLETED => __('Completed'),
            self::SKIPPED => __('Skipped'),
            self::CANCELLED => __('Cancelled'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SCHEDULED => 'yellow',
            self::IN_PROGRESS => 'blue',
            self::COMPLETED => 'green',
            self::SKIPPED => 'orange',
            self::CANCELLED => 'red',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::SCHEDULED => 'clock',
            self::IN_PROGRESS => 'arrow-path',
            self::COMPLETED => 'check-circle',
            self::SKIPPED => 'arrow-right',
            self::CANCELLED => 'x-circle',
        };
    }

    public function canEdit(): bool
    {
        return \in_array($this, [self::SCHEDULED, self::IN_PROGRESS, self::SKIPPED]);
    }

    public function isFinalized(): bool
    {
        return \in_array($this, [self::COMPLETED, self::CANCELLED]);
    }

    public function canGenerateInvoice(): bool
    {
        return $this === self::COMPLETED;
    }

    public function canTransitionTo(self $newStatus): bool
    {
        return match ($this) {
            self::SCHEDULED => in_array($newStatus, [self::IN_PROGRESS, self::COMPLETED, self::SKIPPED, self::CANCELLED]),
            self::IN_PROGRESS => in_array($newStatus, [self::COMPLETED, self::CANCELLED]),
            self::COMPLETED => false,
            self::SKIPPED => in_array($newStatus, [self::SCHEDULED, self::CANCELLED]),
            self::CANCELLED => false,
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::SCHEDULED, self::IN_PROGRESS]);
    }
}
