<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

final class CommentId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new \InvalidArgumentException('Comment ID must be positive');
        }
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(CommentId $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
