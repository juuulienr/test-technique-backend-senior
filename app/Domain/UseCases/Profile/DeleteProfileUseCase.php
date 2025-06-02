<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Profile;

use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Models\Profile;
use App\Services\ImageService;

final class DeleteProfileUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private CommentRepositoryInterface $commentRepository,
        private ImageService $imageService
    ) {
    }

    public function execute(Profile $profile): void
    {
        // Supprimer l'image associée
        if ($profile->image) {
            $this->imageService->delete($profile->image);
        }

        // Supprimer les commentaires associés via le repository
        $this->commentRepository->deleteByProfileId($profile->id);

        // Supprimer le profil
        $this->profileRepository->delete($profile);
    }
}
