<?php

namespace App\Domains\Bill\Enums;

enum BillStatus: string
{
    case OPEN = 'OPEN';
    case PAID = 'PAID';
    case TOEXPIRE = 'TOEXPIRE';
    case LATE = 'LATE';
    case PAIDPARTIAL = 'PAIDPARTIAL';


    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Aberto',
            self::PAID => 'Pago',
            self::LATE => 'Vencido',
            self::TOEXPIRE => 'A Vencer',
            self::PAIDPARTIAL => 'Pago Parcial',
        };
    }
}
