<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\ProfileApplicationService;
use App\Application\DTOs\CreateProfileDTO;
use App\Application\DTOs\UpdateProfileDTO;
use App\Domain\Entities\Profile;
use App\Domain\ValueObjects\PersonName;
use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileStatut;
use App\Infrastructure\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfileApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(ProfileApplicationService::class);
        Storage::fake('public');
    }

    public function test_it_creates_profile_through_service(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('profile.jpg');

        $createProfileDTO = new CreateProfileDTO(
            name: new PersonName('Dupont', 'Jean'),
            statut: ProfileStatut::ACTIF,
            imagePath: '', // Sera géré par le Use Case
            adminId: $admin->id
        );

        $profile = $this->service->createProfile($createProfileDTO, $image);

        $this->assertInstanceOf(Profile::class, $profile);
        $this->assertEquals('Dupont', $profile->getName()->nom());
        $this->assertEquals('Jean', $profile->getName()->prenom());
        $this->assertEquals(ProfileStatut::ACTIF, $profile->getStatut());
        $this->assertEquals($admin->id, $profile->getAdminId()->getValue());
        $this->assertNotEmpty($profile->getImagePath());
        $this->assertTrue(Storage::disk('public')->exists($profile->getImagePath()));
    }

    public function test_it_updates_profile_through_service(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('profile.jpg');

        // Créer un profil
        $createProfileDTO = new CreateProfileDTO(
            name: new PersonName('Dupont', 'Jean'),
            statut: ProfileStatut::ACTIF,
            imagePath: '',
            adminId: $admin->id
        );

        $profile = $this->service->createProfile($createProfileDTO, $image);
        $profileId = $profile->getId();

        // Mettre à jour le profil
        $updateProfileDTO = new UpdateProfileDTO(
            name: new PersonName('Martin', 'Pierre'),
            statut: ProfileStatut::INACTIF,
            imagePath: null
        );

        $updatedProfile = $this->service->updateProfile($profileId, $updateProfileDTO);

        $this->assertEquals('Martin', $updatedProfile->getName()->nom());
        $this->assertEquals('Pierre', $updatedProfile->getName()->prenom());
        $this->assertEquals(ProfileStatut::INACTIF, $updatedProfile->getStatut());
    }

    public function test_it_deletes_profile_through_service(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('profile.jpg');

        // Créer un profil
        $createProfileDTO = new CreateProfileDTO(
            name: new PersonName('Dupont', 'Jean'),
            statut: ProfileStatut::ACTIF,
            imagePath: '',
            adminId: $admin->id
        );

        $profile = $this->service->createProfile($createProfileDTO, $image);
        $profileId = $profile->getId();
        $imagePath = $profile->getImagePath();

        // Vérifier que l'image existe
        $this->assertTrue(Storage::disk('public')->exists($imagePath));

        // Supprimer le profil
        $this->service->deleteProfile($profileId);

        // Vérifier que le profil n'existe plus
        $deletedProfile = $this->service->getProfileById($profileId);
        $this->assertNull($deletedProfile);

        // Vérifier que l'image a été supprimée
        $this->assertFalse(Storage::disk('public')->exists($imagePath));
    }

    public function test_it_gets_active_profiles(): void
    {
        $admin = Admin::factory()->create();

        // Créer des profils avec différents statuts
        $activeProfile = $this->createTestProfile($admin->id, ProfileStatut::ACTIF);
        $inactiveProfile = $this->createTestProfile($admin->id, ProfileStatut::INACTIF);
        $pendingProfile = $this->createTestProfile($admin->id, ProfileStatut::EN_ATTENTE);

        $activeProfiles = $this->service->getActiveProfiles();

        $this->assertCount(1, $activeProfiles);
        $this->assertEquals($activeProfile->getId()->getValue(), $activeProfiles[0]->getId()->getValue());
    }

    public function test_it_checks_profile_ownership(): void
    {
        $admin1 = Admin::factory()->create();
        $admin2 = Admin::factory()->create();

        $profile = $this->createTestProfile($admin1->id, ProfileStatut::ACTIF);

        $this->assertTrue(
            $this->service->isProfileOwnedByAdmin(
                $profile->getId(),
                new AdminId($admin1->id)
            )
        );

        $this->assertFalse(
            $this->service->isProfileOwnedByAdmin(
                $profile->getId(),
                new AdminId($admin2->id)
            )
        );
    }

    private function createTestProfile(int $adminId, ProfileStatut $statut): Profile
    {
        $image = UploadedFile::fake()->image('test.jpg');

        $createProfileDTO = new CreateProfileDTO(
            name: new PersonName('Test', 'User'),
            statut: $statut,
            imagePath: '',
            adminId: $adminId
        );

        return $this->service->createProfile($createProfileDTO, $image);
    }
} 