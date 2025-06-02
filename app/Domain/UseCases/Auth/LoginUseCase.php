<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Auth;

use App\Domain\DTOs\AuthDTO;
use App\Domain\Repositories\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class LoginUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function execute(AuthDTO $authDTO): string
    {
        $admin = $this->adminRepository->findByEmail($authDTO->email);

        if (!$admin || !Hash::check($authDTO->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['Les informations d\'identification fournies sont incorrectes.'],
            ]);
        }

        // Révoquer les anciens tokens pour la sécurité
        $this->adminRepository->revokeAllTokens($admin);

        return $this->adminRepository->createAuthToken($admin);
    }
}
