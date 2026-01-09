<?php

namespace App\Enums;

enum KsefStatus: string
{
    case PENDING = 'pending';
    case SENT_TO_KSEF = 'sent_to_ksef';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case PAID = 'paid';

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
        return [
            self::PENDING->value => self::PENDING->label(),
            self::SENT_TO_KSEF->value => self::SENT_TO_KSEF->label(),
            self::ACCEPTED->value => self::ACCEPTED->label(),
            self::REJECTED->value => self::REJECTED->label(),
            self::PAID->value => self::PAID->label(),
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::PENDING => __('Pending'),
            self::SENT_TO_KSEF => __('Sent to KSeF'),
            self::ACCEPTED => __('Accepted'),
            self::REJECTED => __('Rejected'),
            self::PAID => __('Paid'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'gray',
            self::SENT_TO_KSEF => 'blue',
            self::ACCEPTED => 'green',
            self::REJECTED => 'red',
            self::PAID => 'purple',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::PENDING => 'clock',
            self::SENT_TO_KSEF => 'upload',
            self::ACCEPTED => 'check-circle',
            self::REJECTED => 'x-circle',
            self::PAID => 'currency-dollar',
        };
    }

    public function canResend(): bool
    {
        return \in_array($this, [self::PENDING, self::REJECTED]);
    }

    public function isSuccessful(): bool
    {
        return \in_array($this, [self::ACCEPTED, self::PAID]);
    }

    public function needsAttention(): bool
    {
        return \in_array($this, [self::PENDING, self::REJECTED]);
    }

    public function nextStatuses(): array
    {
        return match($this) {
            self::PENDING => [self::SENT_TO_KSEF],
            self::SENT_TO_KSEF => [self::ACCEPTED, self::REJECTED],
            self::ACCEPTED => [self::PAID],
            self::REJECTED => [self::SENT_TO_KSEF],
            self::PAID => [],
        };
    }
}
