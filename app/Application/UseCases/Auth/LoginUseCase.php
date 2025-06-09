<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\AuthDTO;
use App\Domain\Entities\Admin;
use App\Domain\Exceptions\AuthenticationException;
use App\Domain\Ports\PasswordHasherPortInterface;
use App\Domain\Ports\TokenManagerPortInterface;
use App\Domain\Repositories\AdminRepositoryInterface;

/**
 * Use Case pour l'authentification d'un administrateur
 */
final class LoginUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordHasherPortInterface $passwordHasher,
        private TokenManagerPortInterface $tokenManager
    ) {
    }

    /**
     * Authentifie un admin et retourne un token
     */
    public function execute(AuthDTO $authDTO): string
    {
        // Rechercher l'admin par email
        $admin = $this->adminRepository->findByEmail($authDTO->email);

        if (!$admin) {
            throw AuthenticationException::invalidCredentials();
        }

        // Vérifier le mot de passe
        if (!$admin->verifyPassword($authDTO->password, [$this->passwordHasher, 'verify'])) {
            throw AuthenticationException::invalidCredentials();
        }

        // Révoquer les anciens tokens pour la sécurité
        $this->tokenManager->revokeAllTokens($admin->getId());

        // Créer un nouveau token
        return $this->tokenManager->createToken($admin->getId());
    }
}
