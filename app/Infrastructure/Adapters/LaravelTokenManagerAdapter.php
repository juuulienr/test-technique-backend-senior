<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Ports\TokenManagerPortInterface;
use App\Domain\ValueObjects\AdminId;
use App\Infrastructure\Models\Admin as EloquentAdmin;

/**
 * Adapter Laravel Sanctum pour la gestion des tokens
 */
final class LaravelTokenManagerAdapter implements TokenManagerPortInterface
{
    public function createToken(AdminId $adminId, string $tokenName = 'auth_token'): string
    {
        $eloquentAdmin = EloquentAdmin::findOrFail($adminId->getValue());

        return $eloquentAdmin->createToken($tokenName)->plainTextToken;
    }

    public function revokeAllTokens(AdminId $adminId): void
    {
        $eloquentAdmin = EloquentAdmin::find($adminId->getValue());

        if ($eloquentAdmin) {
            $eloquentAdmin->tokens()->delete();
        }
    }

    public function validateToken(string $token): ?AdminId
    {
        $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);

        if (!$tokenModel || !$tokenModel->tokenable instanceof EloquentAdmin) {
            return null;
        }

        return new AdminId($tokenModel->tokenable->id);
    }
}
