<?php

namespace Tests\Unit;

use App\Domain\DTOs\CreateProfileDTO;
use App\Domain\DTOs\UpdateProfileDTO;
use App\Domain\ValueObjects\PersonName;
use App\Enums\ProfileStatut;
use App\Models\Admin;
use App\Models\Profile;
use App\Services\ProfileService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileServiceTest extends TestCase
{
    use RefreshDatabase;

    private ProfileService $profileService;

    protected function setUp(): void
    {
        parent::setUp();

        // Utiliser l'injection de dépendances du container Laravel
        $this->profileService = $this->app->make(ProfileService::class);
        Storage::fake('public');
    }

    /**
     * @return CreateProfileDTO
     */
    private function createValidProfileDTO(int $adminId): CreateProfileDTO
    {
        return new CreateProfileDTO(
            name: new PersonName('NomTest', 'PrenomTest'),
            statut: ProfileStatut::ACTIF,
            imagePath: '', // Sera géré par le Use Case
            adminId: $adminId
        );
    }

    public function test_it_creates_a_profile_with_image(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('avatar.jpg');

        $createProfileDTO = $this->createValidProfileDTO($admin->id);

        $profile = $this->profileService->createProfile($createProfileDTO, $image);

        $this->assertModelExists($profile);
        $this->assertEquals('NomTest', $profile->nom);
        $this->assertNotNull($profile->image);
        $this->assertTrue(Storage::disk('public')->exists($profile->image));
    }

    public function test_it_updates_a_profile_and_replaces_image(): void
    {
        $admin = Admin::factory()->create();
        $oldImage = UploadedFile::fake()->image('old.jpg');
        $newImage = UploadedFile::fake()->image('new.jpg');

        $createProfileDTO = $this->createValidProfileDTO($admin->id);
        $profile = $this->profileService->createProfile($createProfileDTO, $oldImage);

        $oldImagePath = $profile->image;
        $this->assertNotNull($oldImagePath);

        $updateProfileDTO = new UpdateProfileDTO(
            name: new PersonName('Pierre', 'PrenomTest'),
            statut: null,
            imagePath: null // Sera géré par le Use Case
        );

        $updated = $this->profileService->updateProfile($profile, $updateProfileDTO, $newImage);

        $this->assertModelExists($updated);
        $this->assertEquals('Pierre', $updated->nom);
        $this->assertNotNull($updated->image);
        $this->assertTrue(Storage::disk('public')->exists($updated->image));
        $this->assertFalse(Storage::disk('public')->exists($oldImagePath));
    }

    public function test_it_updates_profile_without_replacing_image(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('photo.jpg');

        $createProfileDTO = $this->createValidProfileDTO($admin->id);
        $profile = $this->profileService->createProfile($createProfileDTO, $image);

        $oldImagePath = $profile->image;
        $this->assertNotNull($oldImagePath);

        $updateProfileDTO = new UpdateProfileDTO(
            name: new PersonName('NomTest', 'Blanc'),
            statut: null,
            imagePath: null
        );

        $updated = $this->profileService->updateProfile($profile, $updateProfileDTO, null);

        $this->assertModelExists($updated);
        $this->assertEquals('Blanc', $updated->prenom);
        $this->assertEquals($oldImagePath, $updated->image);
        $this->assertTrue(Storage::disk('public')->exists($oldImagePath));
    }

    public function test_it_deletes_profile_and_image(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('img.jpg');

        $createProfileDTO = $this->createValidProfileDTO($admin->id);
        $profile = $this->profileService->createProfile($createProfileDTO, $image);

        $imagePath = $profile->image;
        $this->assertNotNull($imagePath);

        $this->profileService->deleteProfile($profile);

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
        $this->assertFalse(Storage::disk('public')->exists($imagePath));
    }
}
