<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Services\ProfileService;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Profile\UpdateProfileRequest;
use Illuminate\Support\Facades\Storage;

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

        $image = $request->file('image');
        if (!$image) {
            return response()->json(['message' => 'Image is required'], 422);
        }

        $profile = $this->profileService->createProfile(
            $validated,
            $image,
            $user->id
        );

        return response()->json([
          'message' => 'Profil créé avec succès',
          'data' => $profile,
        ], 201);
    }

    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $updated = $this->profileService->updateProfile(
            $profile,
            $request->validated(),
            $request->file('image')
        );

        return response()->json($updated);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        $this->profileService->deleteProfile($profile);

        return response()->json(['message' => 'Profil supprimé avec succès.']);
    }
}
