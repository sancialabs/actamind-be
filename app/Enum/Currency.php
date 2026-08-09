<?php

namespace App\Enum;

enum Currency: string
{
    case Peso = 'PHP';
    case Dollar = 'USD';
    case Yen = 'JPY';
    case Euro = 'EUR';
    case PoundSterling = 'GBP';

    public function label(): string
    {
        return match ($this) {
            self::Peso => 'Philippine Peso',
            self::Dollar => 'US Dollar',
            self::Yen => 'Japanese Yen',
            self::Euro => 'Euro',
            self::PoundSterling => 'British Pound Sterling',
        };
    }

    public function symbol(): string
    {
        return match ($this) {
            self::Peso => '₱',
            self::Dollar => '$',
            self::Yen => '¥',
            self::Euro => '€',
            self::PoundSterling => '£',
        };
    }
}
