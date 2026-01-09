<?php

namespace App\Enums;

enum DocumentType: string
{
    case INVOICE = 'invoice';
    case KPO = 'kpo';

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
            self::INVOICE->value => self::INVOICE->label(),
            self::KPO->value => self::KPO->label(),
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::INVOICE => __('Invoice'),
            self::KPO => __('KPO Document'),
        };
    }

    public function modelClass(): string
    {
        return match($this) {
            self::INVOICE => \App\Models\Invoice::class,
            self::KPO => \App\Models\KpoDocument::class,
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::INVOICE => 'document-text',
            self::KPO => 'clipboard-document-list',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::INVOICE => 'blue',
            self::KPO => 'green',
        };
    }
}
