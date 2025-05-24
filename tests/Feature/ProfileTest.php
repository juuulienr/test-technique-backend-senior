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
}
