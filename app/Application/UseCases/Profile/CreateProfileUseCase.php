<?php

declare(strict_types=1);

namespace App\Domain\UseCases\Profile;

use App\Domain\DTOs\CreateProfileDTO;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Models\Profile;
use App\Services\ImageService;
use Illuminate\Http\UploadedFile;

final class CreateProfileUseCase
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
        private ImageService $imageService
    ) {
    }

    public function execute(CreateProfileDTO $createProfileDTO, UploadedFile $imageFile): Profile
    {
        // Upload de l'image
        $imagePath = $this->imageService->upload($imageFile, 'profiles');

        // Création du profil avec le chemin de l'image uploadée
        $profileData = $createProfileDTO->toArray();
        $profileData['image'] = $imagePath;

        return $this->profileRepository->create($profileData);
    }
}
