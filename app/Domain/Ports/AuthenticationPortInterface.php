<?php

declare(strict_types=1);

namespace App\Domain\Ports;

use App\Domain\ValueObjects\AdminId;

/**
 * Port pour l'authentification
 * Interface que doit implémenter l'adapter d'authentification
 */
interface AuthenticationPortInterface
{
    /**
     * Authentifie un utilisateur avec email/mot de passe
     */
    public function authenticate(string $email, string $password): ?AdminId;

    /**
     * Génère un token pour un utilisateur authentifié
     */
    public function generateToken(AdminId $adminId): string;

    /**
     * Valide un token et retourne l'ID de l'utilisateur
     */
    public function validateToken(string $token): ?AdminId;

    /**
     * Révoque un token
     */
    public function revokeToken(string $token): void;
}
