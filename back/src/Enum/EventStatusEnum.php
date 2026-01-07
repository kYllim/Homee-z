<?php

namespace App\Enum;

enum EventStatusEnum: string
{
    case PREVU = 'prévu';
    case EN_COURS = 'en cours';
    case EN_RETARD = 'en retard';
    case TERMINE = 'terminé';
    case ANNULE = 'annulé';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
