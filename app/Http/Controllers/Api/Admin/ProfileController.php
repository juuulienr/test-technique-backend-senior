<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\DTOs\CreateProfileDTO;
use App\Domain\DTOs\UpdateProfileDTO;
use App\Domain\ValueObjects\PersonName;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Http\Requests\Profile\UpdateProfileRequest;
use App\Http\Responses\ApiResponse;
use App\Services\ProfileService;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;

class ProfileController extends Controller
{
    public function __construct(private ProfileService $profileService)
    {
    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        /** @var Admin $user */
        $user = $request->user();

        /** @var UploadedFile $image */
        $image = $request->file('image');

        $createProfileDTO = new CreateProfileDTO(
            name: new PersonName($request->nom, $request->prenom),
            statut: $request->statut, // Déjà casté par le Request
            imagePath: '', // Sera géré par le Use Case
            adminId: $user->id
        );

        $profile = $this->profileService->createProfile($createProfileDTO, $image);

        return ApiResponse::created($profile, 'Profil créé avec succès');
    }

    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $updateProfileDTO = new UpdateProfileDTO(
            name: ($request->has('nom') && $request->has('prenom'))
                ? new PersonName($request->nom, $request->prenom)
                : null,
            statut: $request->statut ?? null,
            imagePath: null // Sera géré par le Use Case
        );

        $updated = $this->profileService->updateProfile(
            $profile,
            $updateProfileDTO,
            $request->file('image')
        );

        return ApiResponse::success($updated, 'Profil mis à jour avec succès');
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $this->profileService->deleteProfile($profile);

        return ApiResponse::deleted('Profil supprimé avec succès');
    }
}
