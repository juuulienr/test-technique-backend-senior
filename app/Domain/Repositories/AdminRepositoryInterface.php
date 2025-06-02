<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\ValueObjects\Email;
use App\Models\Admin;

interface AdminRepositoryInterface
{
    /**
     * Trouve un admin par son email
     */
    public function findByEmail(Email $email): ?Admin;

    /**
     * Crée un nouvel admin
     */
    public function create(array $data): Admin;

    /**
     * Vérifie si un email existe déjà
     */
    public function emailExists(Email $email): bool;

    /**
     * Trouve un admin par son ID
     */
    public function findById(int $id): ?Admin;

    /**
     * Supprime tous les tokens d'un admin
     */
    public function revokeAllTokens(Admin $admin): void;

    /**
     * Crée un token d'authentification pour l'admin
     */
    public function createAuthToken(Admin $admin, string $tokenName = 'auth_token'): string;
}
