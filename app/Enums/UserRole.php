<?php

namespace App\Enums;

use App\Traits\TranslatableEnums;

enum UserRole: string
{
    // use TranslatableEnums;

    case ADMIN = 'admin';
    case EMPLOYEE = 'employee';
    case DRIVER = 'driver';

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
            self::ADMIN->value => self::ADMIN->label(),
            self::EMPLOYEE->value => self::EMPLOYEE->label(),
            self::DRIVER->value => self::DRIVER->label(),
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::ADMIN => __('Administrator'),
            self::EMPLOYEE => __('Employee'),
            self::DRIVER => __('Driver'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ADMIN => 'red',
            self::EMPLOYEE => 'blue',
            self::DRIVER => 'green',
        };
    }

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function canManageRoutes(): bool
    {
        return in_array($this, [self::ADMIN, self::EMPLOYEE]);
    }

    public function canDrive(): bool
    {
        return $this === self::DRIVER;
    }
}
