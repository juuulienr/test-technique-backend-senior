<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Ports\AuthenticationPortInterface;
use App\Domain\ValueObjects\AdminId;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Adaptateur Laravel pour l'authentification
 * Implémente le port d'authentification
 */
final class LaravelAuthenticationAdapter implements AuthenticationPortInterface
{
    public function authenticate(string $email, string $password): ?AdminId
    {
        $admin = Admin::where('email', $email)->first();
        
        if (!$admin || !Hash::check($password, $admin->password)) {
            return null;
        }

        return new AdminId($admin->id);
    }

    public function generateToken(AdminId $adminId): string
    {
        $admin = Admin::find($adminId->getValue());
        
        if (!$admin) {
            throw new \InvalidArgumentException('Admin not found');
        }

        return $admin->createToken('auth-token')->plainTextToken;
    }

    public function validateToken(string $token): ?AdminId
    {
        $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        
        if (!$tokenModel || !$tokenModel->tokenable instanceof Admin) {
            return null;
        }

        return new AdminId($tokenModel->tokenable->id);
    }

    public function revokeToken(string $token): void
    {
        $tokenModel = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
        
        if ($tokenModel) {
            $tokenModel->delete();
        }
    }
} 