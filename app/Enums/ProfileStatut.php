<?php

namespace App\Enums;

enum ProfileStatut: string
{
    case INACTIF = 'inactif';
    case EN_ATTENTE = 'en attente';
    case ACTIF = 'actif';
}
