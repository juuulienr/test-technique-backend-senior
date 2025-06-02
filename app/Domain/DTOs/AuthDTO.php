<?php

declare(strict_types=1);

namespace App\Domain\DTOs;

use App\Domain\ValueObjects\Email;

final readonly class AuthDTO
{
    public function __construct(
        public Email $email,
        public string $password,
        public ?string $name = null
    ) {
    }

    /**
     * Factory method pour la connexion
     */
    public static function forLogin(string $email, string $password): self
    {
        return new self(
            email: new Email($email),
            password: $password
        );
    }

    /**
     * Factory method pour l'inscription
     */
    public static function forRegister(string $name, string $email, string $password): self
    {
        return new self(
            email: new Email($email),
            password: $password,
            name: $name
        );
    }

    /**
     * Factory method depuis un tableau
     */
    public static function fromArray(array $data): self
    {
        return new self(
            email: new Email($data['email']),
            password: $data['password'],
            name: $data['name'] ?? null
        );
    }

    /**
     * Convertit en tableau pour la persistance
     */
    public function toArray(): array
    {
        $data = [
            'email' => $this->email->value(),
            'password' => $this->password,
        ];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        return $data;
    }

    /**
     * Vérifie si c'est pour une inscription
     */
    public function isForRegistration(): bool
    {
        return $this->name !== null;
    }
}
