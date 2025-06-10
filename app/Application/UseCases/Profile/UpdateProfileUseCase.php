<?php

declare(strict_types=1);

namespace App\Application\UseCases\Profile;

use App\Application\DTOs\UpdateProfileDTO;
use App\Domain\Entities\Profile;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Domain\Ports\ImageManagerPortInterface;
use App\Domain\ValueObjects\ProfileId;
use InvalidArgumentException;

final class UpdateProfileUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private ImageManagerPortInterface $imageManager
    ) {
    }

    public function execute(ProfileId $profileId, UpdateProfileDTO $updateProfileDTO, mixed $newImageFile = null): Profile
    {
        if (!$updateProfileDTO->hasChanges() && $newImageFile === null) {
            throw new InvalidArgumentException('Aucune modification fournie');
        }

        // Récupérer le profil existant
        $profile = $this->profileRepository->findById($profileId);
        if (!$profile) {
            throw new InvalidArgumentException('Profil non trouvé');
        }

        // Appliquer les modifications
        $updatedProfile = $this->applyUpdates($profile, $updateProfileDTO, $newImageFile);

        // Sauvegarder
        return $this->profileRepository->save($updatedProfile);
    }

    private function applyUpdates(Profile $profile, UpdateProfileDTO $updateProfileDTO, mixed $newImageFile): Profile
    {
        $updatedProfile = $profile;

        // Mise à jour du nom si fourni
        if ($updateProfileDTO->name !== null) {
            $updatedProfile = $updatedProfile->changeName($updateProfileDTO->name);
        }

        // Mise à jour du statut si fourni
        if ($updateProfileDTO->statut !== null) {
            $updatedProfile = $updatedProfile->changeStatut($updateProfileDTO->statut);
        }

        // Gestion de la nouvelle image si fournie
        if ($newImageFile !== null) {
            $newImagePath = $this->imageManager->replace(
                $profile->getImagePath(),
                $newImageFile,
                'profiles'
            );
            $updatedProfile = $updatedProfile->changeImage($newImagePath);
        }

        return $updatedProfile;
    }
}
