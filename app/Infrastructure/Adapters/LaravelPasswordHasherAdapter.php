<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Ports\PasswordHasherPortInterface;
use Illuminate\Support\Facades\Hash;

/**
 * Adapter Laravel pour le hashing des mots de passe
 */
final class LaravelPasswordHasherAdapter implements PasswordHasherPortInterface
{
    public function hash(string $password): string
    {
        return Hash::make($password);
    }

    public function verify(string $password, string $hash): bool
    {
        return Hash::check($password, $hash);
    }
}
