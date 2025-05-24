<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\StoreProfileRequest;
use App\Services\ProfileService;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;

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
}
