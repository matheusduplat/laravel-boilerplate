<?php

namespace App\Domains\RequestManagement\Enums;


enum TypeRequestManagement: string
{
    case CONSULTATION = "CONSULTATION";
    case EXAM = "EXAM";
    case PROCEDURE = "PROCEDURE";

    public function label(): string
    {
        return match ($this) {
            self::CONSULTATION => "Consulta",
            self::EXAM => "Exame",
            self::PROCEDURE => "Procedimento"
        };
    }
}
