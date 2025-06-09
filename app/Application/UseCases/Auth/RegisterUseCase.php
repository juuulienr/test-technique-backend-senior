<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\AuthDTO;
use App\Domain\Entities\Admin;
use App\Domain\Exceptions\AuthenticationException;
use App\Domain\Ports\PasswordHasherPortInterface;
use App\Domain\Ports\TokenManagerPortInterface;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\ValueObjects\AdminId;
use DateTimeImmutable;
use InvalidArgumentException;

/**
 * Use Case pour l'inscription d'un nouvel administrateur
 */
final class RegisterUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository,
        private PasswordHasherPortInterface $passwordHasher,
        private TokenManagerPortInterface $tokenManager
    ) {
    }

    /**
     * Inscrit un nouvel admin et retourne un token
     */
    public function execute(AuthDTO $authDTO): string
    {
        if (!$authDTO->isForRegistration()) {
            throw new InvalidArgumentException('Le DTO doit contenir un nom pour l\'inscription');
        }

        // Vérifier que l'email n'existe pas déjà
        if ($this->adminRepository->emailExists($authDTO->email)) {
            throw AuthenticationException::emailAlreadyExists();
        }

        // Créer l'entité Admin avec mot de passe hashé
        $hashedPassword = $this->passwordHasher->hash($authDTO->password);
        
        $admin = new Admin(
            id: new AdminId(1), // Temporaire, sera défini par le repository
            name: $authDTO->name,
            email: $authDTO->email,
            hashedPassword: $hashedPassword,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );

        // Sauvegarder l'admin
        $savedAdmin = $this->adminRepository->save($admin);

        // Créer un token pour l'admin
        return $this->tokenManager->createToken($savedAdmin->getId());
    }
}
