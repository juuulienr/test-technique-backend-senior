<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases\Auth;

use App\Application\DTOs\AuthDTO;
use App\Application\UseCases\Auth\LoginUseCase;
use App\Domain\Exceptions\AuthenticationException;
use App\Infrastructure\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Ports\PasswordHasherPortInterface;
use App\Domain\Ports\TokenManagerPortInterface;

class LoginUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private LoginUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRepository = $this->app->make(AdminRepositoryInterface::class);
        $passwordHasher = $this->app->make(PasswordHasherPortInterface::class);
        $tokenManager = $this->app->make(TokenManagerPortInterface::class);
        $this->useCase = new LoginUseCase($adminRepository, $passwordHasher, $tokenManager);
    }

    public function test_it_authenticates_admin_with_valid_credentials(): void
    {
        // Créer un admin de test
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        $authDTO = AuthDTO::forLogin('admin@example.com', 'password123');

        $token = $this->useCase->execute($authDTO);

        $this->assertNotEmpty($token);

        // Vérifier que le token existe dans la base
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $admin->id,
            'tokenable_type' => Admin::class,
        ]);
    }

    public function test_it_revokes_existing_tokens_before_creating_new_one(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Créer un token existant
        $oldToken = $admin->createToken('old_token');

        $authDTO = AuthDTO::forLogin('admin@example.com', 'password123');

        $newToken = $this->useCase->execute($authDTO);

        // L'ancien token doit être supprimé
        $this->assertDatabaseMissing('personal_access_tokens', [
            'id' => $oldToken->accessToken->id,
        ]);

        // Le nouveau token doit exister
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $admin->id,
            'name' => 'auth_token',
        ]);
    }

    public function test_it_throws_exception_for_invalid_email(): void
    {
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Les informations d\'identification fournies sont incorrectes.');

        $authDTO = AuthDTO::forLogin('nonexistent@example.com', 'password123');

        $this->useCase->execute($authDTO);
    }

    public function test_it_throws_exception_for_invalid_password(): void
    {
        Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('correct_password'),
        ]);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Les informations d\'identification fournies sont incorrectes.');

        $authDTO = AuthDTO::forLogin('admin@example.com', 'wrong_password');

        $this->useCase->execute($authDTO);
    }

    public function test_it_handles_admin_without_existing_tokens(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);

        // S'assurer qu'il n'y a pas de tokens existants
        $this->assertEquals(0, $admin->tokens()->count());

        $authDTO = AuthDTO::forLogin('admin@example.com', 'password123');

        $token = $this->useCase->execute($authDTO);

        $this->assertNotEmpty($token);
        $this->assertEquals(1, $admin->tokens()->count());
    }
}
