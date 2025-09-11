<?php

namespace App\Domains\AccreditedNetworks\Enums;

enum StatusAccreditedNetworks: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Ativo',
            self::INACTIVE => 'Inativo',
        };
    }
}
