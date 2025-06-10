<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\Services\AuthApplicationService;
use App\Domain\Exceptions\AuthenticationException;
use App\Infrastructure\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use InvalidArgumentException;

class AuthApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(AuthApplicationService::class);
    }

    public function test_it_registers_admin_and_returns_token(): void
    {
        $token = $this->service->register('Test Admin', 'test@test.com', 'secret123');

        $this->assertNotEmpty($token);
        $this->assertDatabaseHas('admins', [
            'name' => 'Test Admin',
            'email' => 'test@test.com'
        ]);
    }

    public function test_it_throws_exception_for_duplicate_email(): void
    {
        // Créer un admin existant
        Admin::factory()->create(['email' => 'test@test.com']);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Cet email est déjà utilisé.');

        $this->service->register('Another Admin', 'test@test.com', 'secret123');
    }

    public function test_it_logs_in_and_returns_token(): void
    {
        // Créer un admin
        Admin::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('secret123')
        ]);

        $token = $this->service->login('test@test.com', 'secret123');

        $this->assertNotEmpty($token);
    }

    public function test_it_throws_exception_for_invalid_credentials(): void
    {
        // Créer un admin avec un mot de passe différent
        Admin::factory()->create([
            'email' => 'test@test.com',
            'password' => Hash::make('correct_password')
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Les informations d\'identification fournies sont incorrectes.');

        $this->service->login('test@test.com', 'wrong_password');
    }

    public function test_it_throws_exception_for_nonexistent_email(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Les informations d\'identification fournies sont incorrectes.');

        $this->service->login('nonexistent@test.com', 'any_password');
    }
} 