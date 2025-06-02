<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Comment;

use App\Domain\DTOs\CreateCommentDTO;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Admin;
use App\Models\Profile;
use InvalidArgumentException;

final class CreateCommentUseCase
{
    public function __construct(
        private CommentRepositoryInterface $commentRepository
    ) {
    }

    public function execute(CreateCommentDTO $createCommentDTO, Admin $admin, Profile $profile): Comment
    {
        // Vérifier que l'admin n'a pas déjà commenté ce profil
        if ($this->commentRepository->hasAdminCommentedProfile($admin, $profile)) {
            throw new InvalidArgumentException('Vous avez déjà commenté ce profil');
        }

        // Vérifier que l'admin et le profil correspondent aux IDs du DTO
        if ($admin->id !== $createCommentDTO->adminId) {
            throw new InvalidArgumentException('L\'administrateur ne correspond pas');
        }

        if ($profile->id !== $createCommentDTO->profileId) {
            throw new InvalidArgumentException('Le profil ne correspond pas');
        }

        return $this->commentRepository->create($createCommentDTO->toArray());
    }
}
