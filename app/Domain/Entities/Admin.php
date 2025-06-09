<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\Email;
use DateTimeImmutable;

/**
 * Entité de domaine Admin
 * Représente un administrateur dans le système
 */
final class Admin
{
    public function __construct(
        private AdminId $id,
        private string $name,
        private Email $email,
        private string $hashedPassword,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {
    }

    public function getId(): AdminId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getHashedPassword(): string
    {
        return $this->hashedPassword;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Vérifie si le mot de passe correspond
     */
    public function verifyPassword(string $plainPassword, callable $verifier): bool
    {
        return $verifier($plainPassword, $this->hashedPassword);
    }

    /**
     * Change le nom de l'admin
     */
    public function changeName(string $newName): self
    {
        return new self(
            $this->id,
            $newName,
            $this->email,
            $this->hashedPassword,
            $this->createdAt,
            new DateTimeImmutable()
        );
    }

    /**
     * Change l'email de l'admin
     */
    public function changeEmail(Email $newEmail): self
    {
        return new self(
            $this->id,
            $this->name,
            $newEmail,
            $this->hashedPassword,
            $this->createdAt,
            new DateTimeImmutable()
        );
    }

    /**
     * Change le mot de passe de l'admin
     */
    public function changePassword(string $newHashedPassword): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $newHashedPassword,
            $this->createdAt,
            new DateTimeImmutable()
        );
    }
} 