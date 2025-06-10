<?php

declare(strict_types=1);

namespace App\Domain\Ports;

use App\Domain\ValueObjects\AdminId;

/**
 * Port pour la gestion des tokens d'authentification
 * Interface que doit implémenter l'adapter de gestion des tokens
 */
interface TokenManagerPortInterface
{
    /**
     * Crée un nouveau token pour un admin
     */
    public function createToken(AdminId $adminId, string $tokenName = 'auth_token'): string;

    /**
     * Révoque tous les tokens d'un admin
     */
    public function revokeAllTokens(AdminId $adminId): void;

    /**
     * Valide un token et retourne l'ID de l'admin associé
     */
    public function validateToken(string $token): ?AdminId;
}
