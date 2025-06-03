<?php

declare(strict_types=1);

namespace App\Domain\DTOs;

use App\Domain\ValueObjects\PersonName;
use App\Enums\ProfileStatut;

final readonly class CreateProfileDTO
{
    public function __construct(
        public PersonName $name,
        public ProfileStatut $statut,
        public string $imagePath,
        public int $adminId
    ) {
    }

    /**
     * Factory method pour créer depuis des données primitives
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: new PersonName($data['nom'], $data['prenom']),
            statut: ProfileStatut::from($data['statut']),
            imagePath: $data['image_path'],
            adminId: $data['admin_id']
        );
    }

    /**
     * Convertit en tableau pour la persistance
     * @return array<string, int|string>
     */
    public function toArray(): array
    {
        return [
            'nom' => $this->name->nom(),
            'prenom' => $this->name->prenom(),
            'statut' => $this->statut->value,
            'image' => $this->imagePath,
            'admin_id' => $this->adminId,
        ];
    }
}
