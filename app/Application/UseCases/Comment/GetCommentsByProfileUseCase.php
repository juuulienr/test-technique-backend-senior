<?php

declare(strict_types=1);

namespace App\Application\UseCases\Comment;

use App\Domain\Entities\Comment;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\ValueObjects\ProfileId;

/**
 * Use Case pour récupérer les commentaires d'un profil
 * Implémente le pattern CQRS (Query)
 */
final class GetCommentsByProfileUseCase
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {
    }

    /**
     * @return Comment[]
     */
    public function execute(ProfileId $profileId): array
    {
        return $this->commentRepository->findByProfileId($profileId);
    }
} 