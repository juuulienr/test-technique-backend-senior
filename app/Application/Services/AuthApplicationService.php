<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTOs\AuthDTO;
use App\Application\UseCases\Auth\LoginUseCase;
use App\Application\UseCases\Auth\RegisterUseCase;

/**
 * Service d'application pour l'authentification
 * Orchestre les use cases dans la couche Application
 */
final class AuthApplicationService
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private RegisterUseCase $registerUseCase
    ) {
    }

    /**
     * Authentifie un admin avec email/mot de passe
     */
    public function login(string $email, string $password): string
    {
        $authDTO = AuthDTO::forLogin($email, $password);

        return $this->loginUseCase->execute($authDTO);
    }

    /**
     * Inscrit un nouvel admin
     */
    public function register(string $name, string $email, string $password): string
    {
        $authDTO = AuthDTO::forRegister($name, $email, $password);

        return $this->registerUseCase->execute($authDTO);
    }
}
