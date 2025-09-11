<?php

namespace App\Domains\AccreditedNetworks\Enums;


enum TypeServiceAccreditedNetworks: string
{
    case HOSPITAL = 'HOSPITAL';
    case CLINIC = 'CLINIC';
    case LABORATORY = 'LABORATORY';

    public function label(): string
    {
        return match ($this) {
            self::HOSPITAL => 'Hospital',
            self::CLINIC => 'Clínica',
            self::LABORATORY => 'Laboratório'
        };
    }
}
