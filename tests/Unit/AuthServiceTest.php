<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();

        // Utiliser l'injection de dépendances du container Laravel
        $this->authService = $this->app->make(AuthService::class);
    }

    public function test_it_registers_and_returns_token(): void
    {
        $token = $this->authService->register([
          'name' => 'Test',
          'email' => 'test@test.com',
          'password' => 'secret123',
        ]);

        $this->assertNotEmpty($token);
        $this->assertDatabaseHas('admins', ['email' => 'test@test.com']);
    }

    public function test_it_logs_in_and_returns_token(): void
    {
        Admin::create([
          'name' => 'Test',
          'email' => 'test@test.com',
          'password' => Hash::make('secret123'),
        ]);

        $token = $this->authService->login([
          'email' => 'test@test.com',
          'password' => 'secret123',
        ]);

        $this->assertNotEmpty($token);
    }

    public function test_it_fails_login_with_wrong_credentials(): void
    {
        $this->expectException(ValidationException::class);

        Admin::create([
          'name' => 'Test',
          'email' => 'test@test.com',
          'password' => Hash::make('correct'),
        ]);

        $this->authService->login([
          'email' => 'test@test.com',
          'password' => 'wrong',
        ]);
    }
}
