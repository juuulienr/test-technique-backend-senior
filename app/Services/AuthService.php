<?php

namespace App\Services;

use App\Domain\DTOs\AuthDTO;
use App\Domain\UseCases\Auth\LoginUseCase;
use App\Domain\UseCases\Auth\RegisterUseCase;

/**
 * Service d'authentification - couche application
 * Orchestre les Use Cases d'authentification
 */
final class AuthService
{
    public function __construct(
        private LoginUseCase $loginUseCase,
        private RegisterUseCase $registerUseCase
    ) {
    }

    /**
     * Inscription d'un nouvel administrateur
     *
     * @param array{name: string, email: string, password: string} $data
     */
    public function register(array $data): string
    {
        $authDTO = AuthDTO::forRegister(
            name: $data['name'],
            email: $data['email'],
            password: $data['password']
        );

        return $this->registerUseCase->execute($authDTO);
    }

    /**
     * Connexion d'un administrateur
     *
     * @param array{email: string, password: string} $data
     */
    public function login(array $data): string
    {
        $authDTO = AuthDTO::forLogin(
            email: $data['email'],
            password: $data['password']
        );

        return $this->loginUseCase->execute($authDTO);
    }
}
