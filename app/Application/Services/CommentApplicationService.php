<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTOs\CreateCommentDTO;
use App\Application\UseCases\Comment\CreateCommentUseCase;
use App\Application\UseCases\Comment\GetCommentsByProfileUseCase;
use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;

/**
 * Service d'application pour les commentaires
 * Orchestre les use cases dans la couche Application
 */
final class CommentApplicationService
{
    public function __construct(
        private CreateCommentUseCase $createCommentUseCase,
        private GetCommentsByProfileUseCase $getCommentsByProfileUseCase
    ) {
    }

    /**
     * Crée un nouveau commentaire
     */
    public function createComment(CreateCommentDTO $createCommentDTO): Comment
    {
        return $this->createCommentUseCase->execute($createCommentDTO);
    }

    /**
     * Récupère les commentaires d'un profil
     * @return Comment[]
     */
    public function getCommentsByProfile(ProfileId $profileId): array
    {
        return $this->getCommentsByProfileUseCase->execute($profileId);
    }
}
