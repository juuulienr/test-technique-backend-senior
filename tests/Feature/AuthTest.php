<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_register(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
          'name' => 'Admin Test',
          'email' => 'admin@test.com',
          'password' => 'password123',
          'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_admin_can_login(): void
    {
        Admin::factory()->create([
          'email' => 'admin@test.com',
          'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
          'email' => 'admin@test.com',
          'password' => 'password123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['access_token', 'token_type']);
    }

    public function test_admin_cannot_register_with_invalid_email(): void
    {
        $response = $this->postJson('/api/v1/auth/register', [
          'name' => 'Admin Test',
          'email' => 'invalid-email',
          'password' => 'password123',
          'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }

    public function test_admin_cannot_login_with_invalid_email(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
          'email' => 'invalid-email',
          'password' => 'password123',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['email']);
    }
}
