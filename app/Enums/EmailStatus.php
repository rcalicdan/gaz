<?php

namespace App\Enums;

enum EmailStatus: string
{
    case SENT = 'sent';
    case FAILED = 'failed';
    case BOUNCED = 'bounced';

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
            self::SENT->value => self::SENT->label(),
            self::FAILED->value => self::FAILED->label(),
            self::BOUNCED->value => self::BOUNCED->label(),
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::SENT => __('Sent'),
            self::FAILED => __('Failed'),
            self::BOUNCED => __('Bounced'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::SENT => 'green',
            self::FAILED => 'red',
            self::BOUNCED => 'orange',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::SENT => 'check-circle',
            self::FAILED => 'x-circle',
            self::BOUNCED => 'arrow-uturn-left',
        };
    }

    public function isSuccessful(): bool
    {
        return $this === self::SENT;
    }

    public function needsRetry(): bool
    {
        return \in_array($this, [self::FAILED, self::BOUNCED]);
    }
}
