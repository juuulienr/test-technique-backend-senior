<?php

namespace App\Domain\ValueObjects;

enum ProfileStatut: string
{
    case INACTIF = 'inactif';
    case EN_ATTENTE = 'en attente';
    case ACTIF = 'actif';
}
