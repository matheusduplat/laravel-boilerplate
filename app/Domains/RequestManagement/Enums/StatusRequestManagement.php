<?php

namespace App\Domains\RequestManagement\Enums;


enum StatusRequestManagement: string
{
    case WAITING = 'WAITING';
    case UNDER_REVIEW = 'UNDER_REVIEW';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case PENDING = 'PENDING';
    case CANCELED = 'CANCELED';

    public function label(): string
    {
        return match ($this) {
            self::WAITING => 'Aguardando',
            self::UNDER_REVIEW => 'Em análise',
            self::APPROVED => 'Aprovado',
            self::REJECTED => 'Reprovado',
            self::PENDING => 'Pendência',
            self::CANCELED => 'Cancelado',
        };
    }
}
