<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Profile;

use App\Domain\DTOs\UpdateProfileDTO;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Models\Profile;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

final class UpdateProfileUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private ImageService $imageService
    ) {
    }

    public function execute(Profile $profile, UpdateProfileDTO $updateProfileDTO, ?UploadedFile $newImageFile = null): Profile
    {
        if (!$updateProfileDTO->hasChanges() && $newImageFile === null) {
            throw new InvalidArgumentException('Aucune modification fournie');
        }

        $updateData = $updateProfileDTO->toArray();

        // Gestion de la nouvelle image si fournie
        if ($newImageFile !== null) {
            $newImagePath = $this->imageService->replace($profile->image, $newImageFile, 'profiles');
            $updateData['image'] = $newImagePath;
        }

        // Mise à jour du profil
        return $this->profileRepository->update($profile, $updateData);
    }
}
