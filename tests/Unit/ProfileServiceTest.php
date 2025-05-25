<?php

namespace Tests\Unit;

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
        $this->profileService = new ProfileService();
        Storage::fake('public');
    }

    /**
     * @return array{nom: string, prenom: string, statut: string}
     */
    private function validProfileData(): array
    {
        return [
          'nom' => 'NomTest',
          'prenom' => 'PrenomTest',
          'statut' => 'actif',
        ];
    }

    public function test_it_creates_a_profile_with_image(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('avatar.jpg');

        $profile = $this->profileService->createProfile(
            $this->validProfileData(),
            $image,
            $admin->id
        );

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

        $profile = $this->profileService->createProfile(
            $this->validProfileData(),
            $oldImage,
            $admin->id
        );

        $oldImagePath = $profile->image;
        $this->assertNotNull($oldImagePath);

        $updated = $this->profileService->updateProfile($profile, [
          'nom' => 'Pierre',
        ], $newImage);

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

        $profile = $this->profileService->createProfile(
            $this->validProfileData(),
            $image,
            $admin->id
        );

        $oldImagePath = $profile->image;
        $this->assertNotNull($oldImagePath);

        $updated = $this->profileService->updateProfile($profile, [
          'prenom' => 'Blanc',
        ], null);

        $this->assertModelExists($updated);
        $this->assertEquals('Blanc', $updated->prenom);
        $this->assertEquals($oldImagePath, $updated->image);
        $this->assertTrue(Storage::disk('public')->exists($oldImagePath));
    }

    public function test_it_deletes_profile_and_image(): void
    {
        $admin = Admin::factory()->create();
        $image = UploadedFile::fake()->image('img.jpg');

        $profile = $this->profileService->createProfile(
            $this->validProfileData(),
            $image,
            $admin->id
        );

        $imagePath = $profile->image;
        $this->assertNotNull($imagePath);

        $this->profileService->deleteProfile($profile);

        $this->assertDatabaseMissing('profiles', ['id' => $profile->id]);
        $this->assertFalse(Storage::disk('public')->exists($imagePath));
    }
}
