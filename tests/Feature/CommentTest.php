<?php

namespace Tests\Feature;

use App\Infrastructure\Models\Admin;
use App\Infrastructure\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_comment_a_profile(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson("/api/v1/admin/profiles/{$profile->id}/comments", [
          'contenu' => 'Très bon profil',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['message' => 'Commentaire créé avec succès']);
    }

    public function test_admin_cannot_comment_same_profile_twice(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();
        $token = $admin->createToken('test')->plainTextToken;

        // Premier commentaire
        $this->withToken($token)->postJson("/api/v1/admin/profiles/{$profile->id}/comments", [
          'contenu' => 'Premier message',
        ])->assertStatus(201);

        // Deuxième commentaire sur le même profil
        $second = $this->withToken($token)->postJson("/api/v1/admin/profiles/{$profile->id}/comments", [
          'contenu' => 'Je reviens encore',
        ]);

        $second->assertStatus(403)
               ->assertJsonFragment(['message' => 'Vous avez déjà commenté ce profil']);
    }

    public function test_contenu_field_is_required(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();
        $token = $admin->createToken('test')->plainTextToken;

        $response = $this->withToken($token)->postJson("/api/v1/admin/profiles/{$profile->id}/comments", []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['contenu']);
    }
}
