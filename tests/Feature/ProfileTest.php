<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_profile_with_image(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $token = $admin->createToken('test_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
          ->postJson('/api/admin/profiles', [
            'nom' => 'Jean',
            'prenom' => 'Dupont',
            'statut' => 'en attente',
            'image' => UploadedFile::fake()->image('photo.jpg'),
          ]);

        $response->assertStatus(201)
                 ->assertJsonStructure(['message', 'data' => ['id', 'nom', 'prenom', 'image', 'statut', 'admin_id']]);

        $imagePath = 'images/' . basename($response['data']['image']);
        $this->assertTrue(Storage::disk('public')->exists($imagePath), "L'image n'existe pas dans le stockage");
    }


    public function test_admin_can_update_own_profile(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $token = $admin->createToken('token')->plainTextToken;

        $responseCreate = $this->withToken($token)->postJson('/api/admin/profiles', [
          'nom' => 'John',
          'prenom' => 'Doe',
          'statut' => 'en attente',
          'image' => UploadedFile::fake()->image('img.jpg'),
        ]);

        $profileId = $responseCreate['data']['id'];

        $responseUpdate = $this->withToken($token)->putJson("/api/admin/profiles/{$profileId}", [
          'nom' => 'Jean',
        ]);

        $responseUpdate->assertStatus(200)
                      ->assertJsonFragment(['nom' => 'Jean']);
    }

    public function test_admin_can_delete_own_profile(): void
    {
        Storage::fake('public');

        $admin = Admin::factory()->create();
        $token = $admin->createToken('token')->plainTextToken;

        $responseCreate = $this->withToken($token)->postJson('/api/admin/profiles', [
          'nom' => 'John',
          'prenom' => 'Doe',
          'statut' => 'en attente',
          'image' => UploadedFile::fake()->image('img.jpg'),
        ]);

        $profileId = $responseCreate['data']['id'];

        $responseDelete = $this->withToken($token)->deleteJson("/api/admin/profiles/{$profileId}");

        $responseDelete->assertStatus(200)
                      ->assertJsonFragment(['message' => 'Profil supprimé avec succès.']);
    }

}
