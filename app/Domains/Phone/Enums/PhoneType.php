<?php

namespace App\Domains\Phone\Enums;

enum PhoneType: string
{
    case MOBILE = 'mobile';
    case FIXED = 'fixed';

    public function label(): string
    {
        return match ($this) {
            self::MOBILE => 'Celular',
            self::FIXED => 'Fixo',
        };
    }
}
