<?php

declare(strict_types=1);

namespace App\Application\UseCases\Profile;

use App\Domain\Entities\Profile;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Domain\Ports\ImageManagerPortInterface;
use App\Domain\ValueObjects\ProfileId;
use InvalidArgumentException;

final class DeleteProfileUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private CommentRepositoryInterface $commentRepository,
        private ImageManagerPortInterface $imageManager
    ) {
    }

    public function execute(ProfileId $profileId): void
    {
        // Récupérer le profil
        $profile = $this->profileRepository->findById($profileId);
        if (!$profile) {
            throw new InvalidArgumentException('Profil non trouvé');
        }

        // Supprimer l'image associée
        if (!empty($profile->getImagePath())) {
            $this->imageManager->delete($profile->getImagePath());
        }

        // Supprimer les commentaires associés via le repository
        $this->commentRepository->deleteByProfileId($profileId);

        // Supprimer le profil
        $this->profileRepository->delete($profile);
    }
}
