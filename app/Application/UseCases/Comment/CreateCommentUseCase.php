<?php

declare(strict_types=1);

namespace App\Application\UseCases\Comment;

use App\Application\DTOs\CreateCommentDTO;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;
use InvalidArgumentException;

final class CreateCommentUseCase
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {
    }

    public function execute(CreateCommentDTO $createCommentDTO): Comment
    {
        $adminId = new AdminId($createCommentDTO->adminId);
        $profileId = new ProfileId($createCommentDTO->profileId);

        // Vérifier que l'admin n'a pas déjà commenté ce profil
        if ($this->commentRepository->hasAdminCommentedProfile($adminId, $profileId)) {
            throw new InvalidArgumentException('Vous avez déjà commenté ce profil');
        }

        // Créer l'entité Comment (sans ID car sera défini par le repository)
        $comment = new Comment(
            id: new \App\Domain\ValueObjects\CommentId(1), // Temporaire, sera remplacé par le repository
            contenu: $createCommentDTO->contenu,
            adminId: $adminId,
            profileId: $profileId,
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable()
        );

        return $this->commentRepository->save($comment);
    }
}
