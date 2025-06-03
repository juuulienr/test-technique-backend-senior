<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Collection;

interface CommentRepositoryInterface
{
    /**
     * Trouve un commentaire par son ID
     */
    public function findById(int $id): ?Comment;

    /**
     * Crée un nouveau commentaire
     * @param array<string, mixed> $data
     */
    public function create(array $data): Comment;

    /**
     * Supprime un commentaire
     */
    public function delete(Comment $comment): void;

    /**
     * Vérifie si un admin a déjà commenté un profil
     */
    public function hasAdminCommentedProfile(Admin $admin, Profile $profile): bool;

    /**
     * Récupère tous les commentaires d'un profil
     * @return Collection<int, Comment>
     */
    public function findByProfileId(int $profileId): Collection;

    /**
     * Récupère tous les commentaires d'un admin
     * @return Collection<int, Comment>
     */
    public function findByAdminId(int $adminId): Collection;

    /**
     * Supprime tous les commentaires d'un profil
     */
    public function deleteByProfileId(int $profileId): int;

    /**
     * Compte le nombre de commentaires d'un profil
     */
    public function countByProfileId(int $profileId): int;

    /**
     * Récupère les commentaires récents (avec pagination potentielle)
     * @return Collection<int, Comment>
     */
    public function findRecent(int $limit = 10): Collection;
}
