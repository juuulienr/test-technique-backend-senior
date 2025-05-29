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
        $user = $request->user();
        if (!$user instanceof Admin) {
            throw new \RuntimeException('User must be an admin');
        }

        /** @var array{nom: string, prenom: string, statut: string} */
        $validated = $request->validated();

        $profile = $this->profileService->createProfile(
            $validated,
            $request->file('image'),
            $user->id
        );

        return response()->json([
          'message' => 'Profil créé avec succès',
          'data' => $profile,
        ], 201);
    }


    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        /** @var Admin|null $user */
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($profile->admin_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $updated = $this->profileService->updateProfile(
            $profile,
            $request->validated(),
            $request->file('image')
        );

        return response()->json($updated);
    }

    public function destroy(Profile $profile): JsonResponse
    {
        /** @var Admin|null $user */
        $user = auth('admin')->user();
        if (!$user || $profile->admin_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $this->profileService->deleteProfile($profile);

        return response()->json(['message' => 'Profil supprimé avec succès.']);
    }

}
