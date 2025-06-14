<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

final readonly class Email
{
    public function __construct(private string $value)
    {
        $this->validate();
    }

    public function value(): string
    {
        return $this->value;
    }

    public function domain(): string
    {
        $atPosition = strpos($this->value, '@');
        if ($atPosition === false) {
            throw new InvalidArgumentException('Email invalide : caractère @ manquant');
        }
        return substr($this->value, $atPosition + 1);
    }

    public function local(): string
    {
        $atPosition = strpos($this->value, '@');
        if ($atPosition === false) {
            throw new InvalidArgumentException('Email invalide : caractère @ manquant');
        }
        return substr($this->value, 0, $atPosition);
    }

    public function equals(Email $other): bool
    {
        return $this->value === $other->value;
    }

    private function validate(): void
    {
        if (empty(trim($this->value))) {
            throw new InvalidArgumentException('L\'email ne peut pas être vide');
        }

        if (strlen($this->value) > 255) {
            throw new InvalidArgumentException('L\'email ne peut pas dépasser 255 caractères');
        }

        if (!filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format d\'email invalide');
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
