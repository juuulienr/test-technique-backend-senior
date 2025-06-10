<?php

declare(strict_types=1);

namespace App\Application\UseCases\Profile;

use App\Application\DTOs\CreateProfileDTO;
use App\Domain\Entities\Profile;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Domain\Ports\ImageManagerPortInterface;
use App\Domain\ValueObjects\AdminId;

final class CreateProfileUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private ImageManagerPortInterface $imageManager
    ) {
    }

    public function execute(CreateProfileDTO $createProfileDTO, mixed $imageFile): Profile
    {
        // Upload de l'image via le port
        $imagePath = $this->imageManager->upload($imageFile, 'profiles');

        // Créer l'entité Profile Domain
        $profile = Profile::create(
            name: $createProfileDTO->name,
            statut: $createProfileDTO->statut,
            imagePath: $imagePath,
            adminId: new AdminId($createProfileDTO->adminId)
        );

        // Sauvegarder via le repository
        return $this->profileRepository->save($profile);
    }
}
