<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\PersonName;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileStatut;
use DateTimeImmutable;

/**
 * Entité Profile dans la couche Domain
 * Représente un profil pur sans dépendances framework
 */
final class Profile
{
    public function __construct(
        private ProfileId $id,
        private PersonName $name,
        private ProfileStatut $statut,
        private string $imagePath,
        private AdminId $adminId,
        private DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {
    }

    /**
     * Factory method pour créer un nouveau profil
     */
    public static function create(
        PersonName $name,
        ProfileStatut $statut,
        string $imagePath,
        AdminId $adminId
    ): self {
        return new self(
            id: new ProfileId(0), // Sera défini par le repository
            name: $name,
            statut: $statut,
            imagePath: $imagePath,
            adminId: $adminId,
            createdAt: new DateTimeImmutable(),
            updatedAt: new DateTimeImmutable()
        );
    }

    /**
     * Factory method pour reconstituer depuis persistance
     */
    public static function fromPersistence(
        ProfileId $id,
        PersonName $name,
        ProfileStatut $statut,
        string $imagePath,
        AdminId $adminId,
        DateTimeImmutable $createdAt,
        DateTimeImmutable $updatedAt
    ): self {
        return new self(
            id: $id,
            name: $name,
            statut: $statut,
            imagePath: $imagePath,
            adminId: $adminId,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );
    }

    // Getters
    public function getId(): ProfileId
    {
        return $this->id;
    }

    public function getName(): PersonName
    {
        return $this->name;
    }

    public function getStatut(): ProfileStatut
    {
        return $this->statut;
    }

    public function getImagePath(): string
    {
        return $this->imagePath;
    }

    public function getAdminId(): AdminId
    {
        return $this->adminId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Méthodes métier
    public function isActive(): bool
    {
        return $this->statut === ProfileStatut::ACTIF;
    }

    public function isOwnedBy(AdminId $adminId): bool
    {
        return $this->adminId->equals($adminId);
    }

    /**
     * Met à jour le nom du profil
     */
    public function changeName(PersonName $newName): self
    {
        return new self(
            id: $this->id,
            name: $newName,
            statut: $this->statut,
            imagePath: $this->imagePath,
            adminId: $this->adminId,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable()
        );
    }

    /**
     * Met à jour le statut du profil
     */
    public function changeStatut(ProfileStatut $newStatut): self
    {
        return new self(
            id: $this->id,
            name: $this->name,
            statut: $newStatut,
            imagePath: $this->imagePath,
            adminId: $this->adminId,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable()
        );
    }

    /**
     * Met à jour l'image du profil
     */
    public function changeImage(string $newImagePath): self
    {
        return new self(
            id: $this->id,
            name: $this->name,
            statut: $this->statut,
            imagePath: $newImagePath,
            adminId: $this->adminId,
            createdAt: $this->createdAt,
            updatedAt: new DateTimeImmutable()
        );
    }

    /**
     * Met à jour l'ID (utilisé par le repository après insertion)
     */
    public function withId(ProfileId $id): self
    {
        return new self(
            id: $id,
            name: $this->name,
            statut: $this->statut,
            imagePath: $this->imagePath,
            adminId: $this->adminId,
            createdAt: $this->createdAt,
            updatedAt: $this->updatedAt
        );
    }
}
