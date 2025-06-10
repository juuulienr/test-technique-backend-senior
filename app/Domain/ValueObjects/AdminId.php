<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class AdminId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Admin ID must be positive');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(AdminId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
