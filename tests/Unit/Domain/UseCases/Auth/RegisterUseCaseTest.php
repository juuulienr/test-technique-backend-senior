<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases\Auth;

use App\Domain\DTOs\AuthDTO;
use App\Domain\UseCases\Auth\RegisterUseCase;
use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use Tests\TestCase;
use App\Domain\Repositories\AdminRepositoryInterface;

class RegisterUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private RegisterUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $adminRepository = $this->app->make(AdminRepositoryInterface::class);
        $this->useCase = new RegisterUseCase($adminRepository);
    }

    public function test_it_registers_new_admin_successfully(): void
    {
        $authDTO = AuthDTO::forRegister(
            'John Doe',
            'john@example.com',
            'password123'
        );

        $token = $this->useCase->execute($authDTO);

        $this->assertNotEmpty($token);

        // Vérifier que l'admin a été créé
        $this->assertDatabaseHas('admins', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        // Vérifier que le mot de passe est hashé
        $admin = Admin::where('email', 'john@example.com')->first();
        $this->assertNotNull($admin);
        $this->assertTrue(Hash::check('password123', $admin->password));

        // Vérifier qu'un token a été créé
        $this->assertDatabaseHas('personal_access_tokens', [
            'tokenable_id' => $admin->id,
            'tokenable_type' => Admin::class,
            'name' => 'auth_token',
        ]);
    }

    public function test_it_throws_exception_for_login_dto(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le DTO doit contenir un nom pour l\'inscription');

        $authDTO = AuthDTO::forLogin('john@example.com', 'password123');

        $this->useCase->execute($authDTO);
    }

    public function test_it_throws_exception_for_duplicate_email(): void
    {
        // Créer un admin existant
        Admin::factory()->create(['email' => 'existing@example.com']);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cet email est déjà utilisé');

        $authDTO = AuthDTO::forRegister(
            'New User',
            'existing@example.com',
            'password123'
        );

        $this->useCase->execute($authDTO);
    }

    public function test_it_creates_admin_with_different_valid_names(): void
    {
        $testCases = [
            'Jean Dupont',
            'Marie-Claire O\'Connor',
            'François-Xavier',
            'Ana',
        ];

        foreach ($testCases as $index => $name) {
            $authDTO = AuthDTO::forRegister(
                $name,
                "user{$index}@example.com",
                'password123'
            );

            $token = $this->useCase->execute($authDTO);

            $this->assertNotEmpty($token);
            $this->assertDatabaseHas('admins', [
                'name' => $name,
                'email' => "user{$index}@example.com",
            ]);
        }
    }

    public function test_it_handles_empty_name_as_registration(): void
    {
        // Un nom vide est techniquement valide pour l'inscription (string vide !== null)
        $authDTO = AuthDTO::forRegister(
            '',
            'empty@example.com',
            'password123'
        );

        $token = $this->useCase->execute($authDTO);

        $this->assertNotEmpty($token);
        $this->assertDatabaseHas('admins', [
            'name' => '',
            'email' => 'empty@example.com',
        ]);
    }

    public function test_it_stores_hashed_password(): void
    {
        $plainPassword = 'my-secure-password-123';

        $authDTO = AuthDTO::forRegister(
            'Test User',
            'test@example.com',
            $plainPassword
        );

        $this->useCase->execute($authDTO);

        $admin = Admin::where('email', 'test@example.com')->first();
        $this->assertNotNull($admin);

        // Le mot de passe stocké ne doit pas être en clair
        $this->assertNotEquals($plainPassword, $admin->password);

        // Mais doit être vérifiable avec Hash::check
        $this->assertTrue(Hash::check($plainPassword, $admin->password));
    }
}
