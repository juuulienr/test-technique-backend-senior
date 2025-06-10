<?php

declare(strict_types=1);

namespace App\Application\DTOs;

use App\Domain\ValueObjects\PersonName;
use App\Domain\ValueObjects\ProfileStatut;

final readonly class UpdateProfileDTO
{
    public function __construct(
        public ?PersonName $name = null,
        public ?ProfileStatut $statut = null,
        public ?string $imagePath = null
    ) {
    }

    /**
     * Factory method pour créer depuis des données primitives
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        $name = null;
        if (isset($data['nom']) && isset($data['prenom'])) {
            $name = new PersonName($data['nom'], $data['prenom']);
        }

        $statut = isset($data['statut']) ? ProfileStatut::from($data['statut']) : null;
        $imagePath = $data['image_path'] ?? null;

        return new self(
            name: $name,
            statut: $statut,
            imagePath: $imagePath
        );
    }

    /**
     * Convertit en tableau pour la persistance (seulement les champs non null)
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['nom'] = $this->name->nom();
            $data['prenom'] = $this->name->prenom();
        }

        if ($this->statut !== null) {
            $data['statut'] = $this->statut->value;
        }

        if ($this->imagePath !== null) {
            $data['image'] = $this->imagePath;
        }

        return $data;
    }

    /**
     * Vérifie si le DTO contient des modifications
     */
    public function hasChanges(): bool
    {
        return $this->name !== null || $this->statut !== null || $this->imagePath !== null;
    }
}
