<?php

namespace App\Http\Controllers\Api\Admin;

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

        /** @var array{nom: string, prenom: string, statut: string} */
        $validated = $request->validated();

        /** @var UploadedFile $image - Garanti par la validation 'required' */
        $image = $request->file('image');

        $profile = $this->profileService->createProfile(
            $validated,
            $image,
            $user->id
        );

        return ApiResponse::created($profile, 'Profil créé avec succès');
    }

    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $updated = $this->profileService->updateProfile(
            $profile,
            $request->validated(),
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
