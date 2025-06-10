<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class ProfileId
{
    public function __construct(private int $value)
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Profile ID must be non-negative');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(ProfileId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
