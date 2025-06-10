<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;
use DateTimeImmutable;

final class Comment
{
    public function __construct(
        private CommentId $id,
        private string $contenu,
        private AdminId $adminId,
        private ProfileId $profileId,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {
    }

    public function getId(): CommentId
    {
        return $this->id;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function getAdminId(): AdminId
    {
        return $this->adminId;
    }

    public function getProfileId(): ProfileId
    {
        return $this->profileId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function updateContenu(string $newContenu): self
    {
        return new self(
            $this->id,
            $newContenu,
            $this->adminId,
            $this->profileId,
            $this->createdAt,
            new DateTimeImmutable()
        );
    }
}
