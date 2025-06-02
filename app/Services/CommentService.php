<?php

namespace App\Services;

use App\Domain\DTOs\CreateCommentDTO;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\UseCases\Comment\CreateCommentUseCase;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;

/**
 * Service de gestion des commentaires - couche application
 * Orchestre les Use Cases et repositories de commentaires
 */
final class CommentService
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private CreateCommentUseCase $createCommentUseCase
    ) {
    }

    /**
     * Vérifie si un admin a déjà commenté un profil
     */
    public function hasAlreadyCommented(Admin $admin, Profile $profile): bool
    {
        return $this->commentRepository->hasAdminCommentedProfile($admin, $profile);
    }

    /**
     * Crée un nouveau commentaire
     */
    public function createComment(string $contenu, Admin $admin, Profile $profile): Comment
    {
        $createCommentDTO = new CreateCommentDTO(
            contenu: $contenu,
            adminId: $admin->id,
            profileId: $profile->id
        );

        return $this->createCommentUseCase->execute($createCommentDTO, $admin, $profile);
    }
}
