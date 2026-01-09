<?php

namespace App\Enums;

enum PickupFrequency: string
{
    case ON_DEMAND = 'on_demand';
    case WEEKLY = 'weekly';
    case BI_WEEKLY = 'bi_weekly';
    case MONTHLY = 'monthly';

    public function label(): string
    {
        return match($this) {
            self::ON_DEMAND => __('On Demand (No Auto-Schedule)'),
            self::WEEKLY => __('Weekly (7 Days)'),
            self::BI_WEEKLY => __('Bi-Weekly (14 Days)'),
            self::MONTHLY => __('Monthly (30 Days)'),
        };
    }

    public function days(): ?int
    {
        return match($this) {
            self::ON_DEMAND => null,
            self::WEEKLY => 7,
            self::BI_WEEKLY => 14,
            self::MONTHLY => 30,
        };
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn($case) => [
            $case->value => $case->label()
        ])->toArray();
    }
}
