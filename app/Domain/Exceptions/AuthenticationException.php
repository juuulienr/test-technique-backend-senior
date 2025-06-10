<?php

declare(strict_types=1);

namespace App\Domain\Exceptions;

use DomainException;

/**
 * Exception de domaine pour les erreurs d'authentification
 */
final class AuthenticationException extends DomainException
{
    public static function invalidCredentials(): self
    {
        return new self('Les informations d\'identification fournies sont incorrectes.');
    }

    public static function emailAlreadyExists(): self
    {
        return new self('Cet email est déjà utilisé.');
    }
}
