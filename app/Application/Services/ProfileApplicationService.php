<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Application\DTOs\CreateProfileDTO;
use App\Application\DTOs\UpdateProfileDTO;
use App\Application\UseCases\Profile\CreateProfileUseCase;
use App\Application\UseCases\Profile\UpdateProfileUseCase;
use App\Application\UseCases\Profile\DeleteProfileUseCase;
use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\Repositories\ProfileRepositoryInterface;

/**
 * Service d'application pour les profils
 * Orchestre les use cases dans la couche Application
 */
final class ProfileApplicationService
{
    public function __construct(
        private CreateProfileUseCase $createProfileUseCase,
        private UpdateProfileUseCase $updateProfileUseCase,
        private DeleteProfileUseCase $deleteProfileUseCase,
        private ProfileRepositoryInterface $profileRepository
    ) {
    }

    /**
     * Crée un nouveau profil avec image
     */
    public function createProfile(CreateProfileDTO $createProfileDTO, mixed $imageFile): Profile
    {
        return $this->createProfileUseCase->execute($createProfileDTO, $imageFile);
    }

    /**
     * Met à jour un profil existant
     */
    public function updateProfile(
        ProfileId $profileId,
        UpdateProfileDTO $updateProfileDTO,
        mixed $imageFile = null
    ): Profile {
        return $this->updateProfileUseCase->execute($profileId, $updateProfileDTO, $imageFile);
    }

    /**
     * Supprime un profil
     */
    public function deleteProfile(ProfileId $profileId): void
    {
        $this->deleteProfileUseCase->execute($profileId);
    }

    /**
     * Récupère un profil par son ID
     */
    public function getProfileById(ProfileId $profileId): ?Profile
    {
        return $this->profileRepository->findById($profileId);
    }

    /**
     * Récupère les profils actifs (pour l'API publique)
     * @return Profile[]
     */
    public function getActiveProfiles(): array
    {
        return $this->profileRepository->findActiveProfiles();
    }

    /**
     * Récupère les profils d'un admin
     * @return Profile[]
     */
    public function getProfilesByAdmin(AdminId $adminId): array
    {
        return $this->profileRepository->findByAdminId($adminId);
    }

    /**
     * Vérifie si un profil appartient à un admin
     */
    public function isProfileOwnedByAdmin(ProfileId $profileId, AdminId $adminId): bool
    {
        $profile = $this->profileRepository->findById($profileId);
        
        return $profile && $profile->isOwnedBy($adminId);
    }
} 