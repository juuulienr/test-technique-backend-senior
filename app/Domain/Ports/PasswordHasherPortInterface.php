<?php

declare(strict_types=1);

namespace App\Domain\Ports;

/**
 * Port pour le hashing des mots de passe
 * Interface que doit implémenter l'adapter de hashing
 */
interface PasswordHasherPortInterface
{
    /**
     * Hash un mot de passe en clair
     */
    public function hash(string $password): string;

    /**
     * Vérifie si un mot de passe en clair correspond au hash
     */
    public function verify(string $password, string $hash): bool;
}
