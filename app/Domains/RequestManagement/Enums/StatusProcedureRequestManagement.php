<?php

namespace App\Domains\RequestManagement\Enums;


enum StatusProcedureRequestManagement: string
{
    case SCHEDULED = 'SCHEDULED';
    case DONE = 'DONE';
    case UNREALIZED = 'UNREALIZED';


    public function label(): string
    {
        return match ($this) {
            self::SCHEDULED => 'Agendado',
            self::DONE => 'Realizado',
            self::UNREALIZED => 'Não realizado',
        };
    }
}
