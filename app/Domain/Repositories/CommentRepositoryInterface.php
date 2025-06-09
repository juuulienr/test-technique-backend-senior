<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;

/**
 * Interface Repository pour les commentaires
 * Port primaire dans l'architecture hexagonale
 */
interface CommentRepositoryInterface
{
    /**
     * Trouve un commentaire par son ID
     */
    public function findById(CommentId $id): ?Comment;

    /**
     * Sauvegarde un commentaire
     */
    public function save(Comment $comment): Comment;

    /**
     * Supprime un commentaire
     */
    public function delete(CommentId $commentId): void;

    /**
     * Vérifie si un admin a déjà commenté un profil
     */
    public function hasAdminCommentedProfile(AdminId $adminId, ProfileId $profileId): bool;

    /**
     * Récupère tous les commentaires d'un profil
     * @return Comment[]
     */
    public function findByProfileId(ProfileId $profileId): array;

    /**
     * Récupère tous les commentaires d'un admin
     * @return Comment[]
     */
    public function findByAdminId(AdminId $adminId): array;

    /**
     * Supprime tous les commentaires d'un profil
     */
    public function deleteByProfileId(ProfileId $profileId): int;

    /**
     * Compte le nombre de commentaires d'un profil
     */
    public function countByProfileId(ProfileId $profileId): int;

    /**
     * Récupère les commentaires récents
     * @return Comment[]
     */
    public function findRecent(int $limit = 10): array;
}
