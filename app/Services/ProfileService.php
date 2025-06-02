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
     *
     * @param array{nom: string, prenom: string, statut: string} $data
     */
    public function createProfile(array $data, UploadedFile $image, int $adminId): Profile
    {
        $createProfileDTO = CreateProfileDTO::fromArray([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'statut' => $data['statut'],
            'image_path' => '', // Sera géré par le Use Case
            'admin_id' => $adminId,
        ]);

        return $this->createProfileUseCase->execute($createProfileDTO, $image);
    }

    /**
     * Met à jour un profil existant
     *
     * @param array{nom?: string, prenom?: string, statut?: string} $data
     */
    public function updateProfile(Profile $profile, array $data, ?UploadedFile $image = null): Profile
    {
        $updateProfileDTO = UpdateProfileDTO::fromArray($data);

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
