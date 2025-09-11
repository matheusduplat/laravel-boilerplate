<?php

namespace App\Domains\Contact\Enums;

enum StatusContact: string
{
    case WAITING = 'WAITING';
    case COMPLETED = 'COMPLETED';
    case INPROGRESS = 'INPROGRESS';
    case CANCELLED = 'CANCELLED';

    public function label(): string
    {
        return match ($this) {
            self::WAITING => 'Aguardando',
            self::COMPLETED => 'Concluído',
            self::INPROGRESS => 'Em andamento',
            self::CANCELLED => 'Cancelado',
        };
    }
}
