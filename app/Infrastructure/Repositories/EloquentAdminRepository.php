<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\ValueObjects\Email;
use App\Models\Admin;

final class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function findByEmail(Email $email): ?Admin
    {
        return Admin::where('email', $email->value())->first();
    }

    public function create(array $data): Admin
    {
        return Admin::create($data);
    }

    public function emailExists(Email $email): bool
    {
        return Admin::where('email', $email->value())->exists();
    }

    public function findById(int $id): ?Admin
    {
        return Admin::find($id);
    }

    public function revokeAllTokens(Admin $admin): void
    {
        $admin->tokens()->delete();
    }

    public function createAuthToken(Admin $admin, string $tokenName = 'auth_token'): string
    {
        return $admin->createToken($tokenName)->plainTextToken;
    }
}
