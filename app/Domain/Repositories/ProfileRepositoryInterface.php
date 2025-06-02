<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Enums\ProfileStatut;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Collection;

interface ProfileRepositoryInterface
{
    /**
     * Trouve un profil par son ID
     */
    public function findById(int $id): ?Profile;

    /**
     * Crée un nouveau profil
     */
    public function create(array $data): Profile;

    /**
     * Met à jour un profil
     */
    public function update(Profile $profile, array $data): Profile;

    /**
     * Supprime un profil
     */
    public function delete(Profile $profile): void;

    /**
     * Récupère tous les profils avec un statut donné
     */
    public function findByStatus(ProfileStatut $statut): Collection;

    /**
     * Récupère tous les profils actifs (pour l'API publique)
     */
    public function findActiveProfiles(): Collection;

    /**
     * Récupère les profils d'un admin
     */
    public function findByAdminId(int $adminId): Collection;

    /**
     * Compte le nombre de profils d'un admin
     */
    public function countByAdminId(int $adminId): int;

    /**
     * Trouve les profils avec leurs commentaires
     */
    public function findWithComments(int $profileId): ?Profile;
}
