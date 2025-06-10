<?php

namespace App\UI\Http\Controllers\Api\Admin;

use App\Application\DTOs\CreateProfileDTO;
use App\Application\DTOs\UpdateProfileDTO;
use App\Application\Services\ProfileApplicationService;
use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\PersonName;
use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\AdminId;
use App\UI\Http\Controllers\Controller;
use App\UI\Http\Requests\Profile\StoreProfileRequest;
use App\UI\Http\Requests\Profile\UpdateProfileRequest;
use App\UI\Http\Responses\ApiResponse;
use App\Infrastructure\Models\Admin;
use App\Domain\ValueObjects\ProfileStatut;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class ProfileController extends Controller
{
    public function __construct(private ProfileApplicationService $profileApplicationService)
    {
    }

    public function store(StoreProfileRequest $request): JsonResponse
    {
        try {
            /** @var Admin $user */
            $user = $request->user();

            $createProfileDTO = new CreateProfileDTO(
                name: new PersonName($request->nom, $request->prenom),
                statut: ProfileStatut::from($request->statut),
                imagePath: '', // Sera géré par le Use Case
                adminId: $user->id
            );

            $profile = $this->profileApplicationService->createProfile(
                $createProfileDTO, 
                $request->file('image')
            );

            return ApiResponse::created($this->profileToArray($profile), 'Profil créé avec succès');
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        }
    }

    public function update(UpdateProfileRequest $request, int $profileId): JsonResponse
    {
        try {
            /** @var Admin $user */
            $user = $request->user();

            // Vérifier que le profil appartient à l'admin
            $adminId = new AdminId($user->id);
            $profileIdVO = new ProfileId($profileId);

            if (!$this->profileApplicationService->isProfileOwnedByAdmin($profileIdVO, $adminId)) {
                return ApiResponse::error('Accès non autorisé', 403);
            }

            // Récupérer le profil existant pour construire le PersonName complet
            $existingProfile = $this->profileApplicationService->getProfileById($profileIdVO);
            if (!$existingProfile) {
                return ApiResponse::error('Profil non trouvé', 404);
            }

            // Construction intelligente du PersonName
            $name = null;
            if ($request->has('nom') || $request->has('prenom')) {
                $nom = $request->has('nom') ? $request->nom : $existingProfile->getName()->nom();
                $prenom = $request->has('prenom') ? $request->prenom : $existingProfile->getName()->prenom();
                $name = new PersonName($nom, $prenom);
            }

            $updateProfileDTO = new UpdateProfileDTO(
                name: $name,
                statut: $request->statut ? ProfileStatut::from($request->statut) : null,
                imagePath: null // Sera géré par le Use Case
            );

            $updated = $this->profileApplicationService->updateProfile(
                $profileIdVO,
                $updateProfileDTO,
                $request->file('image')
            );

            return ApiResponse::success($this->profileToArray($updated), 'Profil mis à jour avec succès');
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        }
    }

    public function destroy(int $profileId): JsonResponse
    {
        try {
            /** @var Admin $user */
            $user = request()->user();

            // Vérifier que le profil appartient à l'admin
            $adminId = new AdminId($user->id);
            $profileIdVO = new ProfileId($profileId);

            if (!$this->profileApplicationService->isProfileOwnedByAdmin($profileIdVO, $adminId)) {
                return ApiResponse::error('Accès non autorisé', 403);
            }

            $this->profileApplicationService->deleteProfile($profileIdVO);

            return ApiResponse::deleted('Profil supprimé avec succès');
        } catch (InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), 422);
        }
    }

    /**
     * Convertit une entité Profile en array pour les réponses JSON
     */
    private function profileToArray(Profile $profile): array
    {
        return [
            'id' => $profile->getId()->getValue(),
            'nom' => $profile->getName()->nom(),
            'prenom' => $profile->getName()->prenom(),
            'image' => $profile->getImagePath() ? asset('storage/' . $profile->getImagePath()) : null,
            'statut' => $profile->getStatut()->value,
            'admin_id' => $profile->getAdminId()->getValue(),
        ];
    }
}
