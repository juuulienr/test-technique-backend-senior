<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class PersonName
{
    public function __construct(
        private string $nom,
        private string $prenom
    ) {
        $this->validate();
    }

    public function nom(): string
    {
        return $this->nom;
    }

    public function prenom(): string
    {
        return $this->prenom;
    }

    public function fullName(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    public function equals(PersonName $other): bool
    {
        return $this->nom === $other->nom && $this->prenom === $other->prenom;
    }

    private function validate(): void
    {
        if (empty(trim($this->nom))) {
            throw new InvalidArgumentException('Le nom ne peut pas être vide');
        }

        if (empty(trim($this->prenom))) {
            throw new InvalidArgumentException('Le prénom ne peut pas être vide');
        }

        if (strlen($this->nom) > 255) {
            throw new InvalidArgumentException('Le nom ne peut pas dépasser 255 caractères');
        }

        if (strlen($this->prenom) > 255) {
            throw new InvalidArgumentException('Le prénom ne peut pas dépasser 255 caractères');
        }

        if (!preg_match('/^[\p{L}\s\'-]+$/u', $this->nom)) {
            throw new InvalidArgumentException('Le nom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets');
        }

        if (!preg_match('/^[\p{L}\s\'-]+$/u', $this->prenom)) {
            throw new InvalidArgumentException('Le prénom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets');
        }
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'nom' => $this->nom,
            'prenom' => $this->prenom,
        ];
    }
}
