<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Auth;

use App\Domain\DTOs\AuthDTO;
use App\Domain\Repositories\AdminRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use InvalidArgumentException;

final class RegisterUseCase
{
    public function __construct(
        private AdminRepositoryInterface $adminRepository
    ) {
    }

    public function execute(AuthDTO $authDTO): string
    {
        if (!$authDTO->isForRegistration()) {
            throw new InvalidArgumentException('Le DTO doit contenir un nom pour l\'inscription');
        }

        // Vérification préalable de l'email
        if ($this->adminRepository->emailExists($authDTO->email)) {
            throw new InvalidArgumentException('Cet email est déjà utilisé');
        }

        try {
            $admin = $this->adminRepository->create([
                'name' => $authDTO->name,
                'email' => $authDTO->email->value(),
                'password' => Hash::make($authDTO->password),
            ]);

            return $this->adminRepository->createAuthToken($admin);
        } catch (QueryException $e) {
            // Fallback au cas où la vérification préalable ne suffirait pas
            if (($e->errorInfo !== null && $e->errorInfo[0] === '23505') || str_contains($e->getMessage(), 'unique constraint')) {
                throw new InvalidArgumentException('Cet email est déjà utilisé');
            }

            throw $e;
        }
    }
}
