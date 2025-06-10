<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileStatut;

interface ProfileRepositoryInterface
{
    /**
     * Trouve un profil par son ID
     */
    public function findById(ProfileId $id): ?Profile;

    /**
     * Sauvegarde un profil (création ou mise à jour)
     */
    public function save(Profile $profile): Profile;

    /**
     * Supprime un profil
     */
    public function delete(Profile $profile): void;

    /**
     * Récupère tous les profils avec un statut donné
     * @return Profile[]
     */
    public function findByStatus(ProfileStatut $statut): array;

    /**
     * Récupère tous les profils actifs (pour l'API publique)
     * @return Profile[]
     */
    public function findActiveProfiles(): array;

    /**
     * Récupère les profils d'un admin
     * @return Profile[]
     */
    public function findByAdminId(AdminId $adminId): array;

    /**
     * Compte le nombre de profils d'un admin
     */
    public function countByAdminId(AdminId $adminId): int;

    /**
     * Vérifie si un profil existe
     */
    public function exists(ProfileId $id): bool;
}
