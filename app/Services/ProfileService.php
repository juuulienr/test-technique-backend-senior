<?php

namespace App\Services;

use App\Domain\DTOs\CreateProfileDTO;
use App\Domain\DTOs\UpdateProfileDTO;
use App\Domain\UseCases\Profile\CreateProfileUseCase;
use App\Domain\UseCases\Profile\UpdateProfileUseCase;
use App\Domain\UseCases\Profile\DeleteProfileUseCase;
use App\Models\Profile;
use Illuminate\Http\UploadedFile;

/**
 * Service de gestion des profils - couche application
 * Orchestre les Use Cases de profils
 */
final class ProfileService
{
    public function __construct(
        private CreateProfileUseCase $createProfileUseCase,
        private UpdateProfileUseCase $updateProfileUseCase,
        private DeleteProfileUseCase $deleteProfileUseCase
    ) {
    }

    /**
     * Crée un nouveau profil
     */
    public function createProfile(CreateProfileDTO $createProfileDTO, UploadedFile $image): Profile
    {
        return $this->createProfileUseCase->execute($createProfileDTO, $image);
    }

    /**
     * Met à jour un profil existant
     */
    public function updateProfile(Profile $profile, UpdateProfileDTO $updateProfileDTO, ?UploadedFile $image = null): Profile
    {
        return $this->updateProfileUseCase->execute($profile, $updateProfileDTO, $image);
    }

    /**
     * Supprime un profil
     */
    public function deleteProfile(Profile $profile): void
    {
        $this->deleteProfileUseCase->execute($profile);
    }
}
