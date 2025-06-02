<?php

declare(strict_types=1);

namespace App\Domain\DTOs;

final readonly class CreateCommentDTO
{
    public function __construct(
        public string $contenu,
        public int $adminId,
        public int $profileId
    ) {
        $this->validate();
    }

    /**
     * Factory method pour créer depuis des données primitives
     */
    public static function fromArray(array $data): self
    {
        return new self(
            contenu: $data['contenu'],
            adminId: $data['admin_id'],
            profileId: $data['profile_id']
        );
    }

    /**
     * Convertit en tableau pour la persistance
     */
    public function toArray(): array
    {
        return [
            'contenu' => $this->contenu,
            'admin_id' => $this->adminId,
            'profile_id' => $this->profileId,
        ];
    }

    /**
     * Validation basique du contenu
     */
    private function validate(): void
    {
        if (empty(trim($this->contenu))) {
            throw new \InvalidArgumentException('Le contenu du commentaire ne peut pas être vide');
        }

        if (strlen($this->contenu) > 5000) {
            throw new \InvalidArgumentException('Le contenu du commentaire ne peut pas dépasser 5000 caractères');
        }
    }
}
