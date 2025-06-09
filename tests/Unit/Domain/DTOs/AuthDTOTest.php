<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\DTOs;

use App\Application\DTOs\AuthDTO;
use App\Domain\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AuthDTOTest extends TestCase
{
    public function test_it_creates_for_login(): void
    {
        $dto = AuthDTO::forLogin('user@example.com', 'password123');

        $this->assertEquals('user@example.com', $dto->email->value());
        $this->assertEquals('password123', $dto->password);
        $this->assertNull($dto->name);
        $this->assertFalse($dto->isForRegistration());
    }

    public function test_it_creates_for_register(): void
    {
        $dto = AuthDTO::forRegister('John Doe', 'john@example.com', 'securePassword');

        $this->assertEquals('john@example.com', $dto->email->value());
        $this->assertEquals('securePassword', $dto->password);
        $this->assertEquals('John Doe', $dto->name);
        $this->assertTrue($dto->isForRegistration());
    }

    public function test_it_creates_from_array_for_login(): void
    {
        $data = [
            'email' => 'test@domain.com',
            'password' => 'myPassword'
        ];

        $dto = AuthDTO::fromArray($data);

        $this->assertEquals('test@domain.com', $dto->email->value());
        $this->assertEquals('myPassword', $dto->password);
        $this->assertNull($dto->name);
        $this->assertFalse($dto->isForRegistration());
    }

    public function test_it_creates_from_array_for_register(): void
    {
        $data = [
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => 'strongPassword123'
        ];

        $dto = AuthDTO::fromArray($data);

        $this->assertEquals('jane@example.com', $dto->email->value());
        $this->assertEquals('strongPassword123', $dto->password);
        $this->assertEquals('Jane Smith', $dto->name);
        $this->assertTrue($dto->isForRegistration());
    }

    public function test_it_converts_login_to_array(): void
    {
        $dto = AuthDTO::forLogin('user@test.com', 'password');

        $expected = [
            'email' => 'user@test.com',
            'password' => 'password',
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_it_converts_register_to_array(): void
    {
        $dto = AuthDTO::forRegister('Test User', 'test@example.com', 'password123');

        $expected = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'name' => 'Test User',
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_it_validates_email_in_constructor(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format d\'email invalide');

        $dto = new AuthDTO(
            email: new Email('invalid-email'),
            password: 'password'
        );

        $this->assertInstanceOf(AuthDTO::class, $dto);
    }

    public function test_it_validates_email_in_for_login(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format d\'email invalide');

        AuthDTO::forLogin('not-an-email', 'password');
    }

    public function test_it_validates_email_in_for_register(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format d\'email invalide');

        AuthDTO::forRegister('John Doe', 'invalid-email-format', 'password');
    }

    public function test_it_validates_email_in_from_array(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format d\'email invalide');

        $data = [
            'email' => 'not.valid.email',
            'password' => 'password'
        ];

        AuthDTO::fromArray($data);
    }

    public function test_it_handles_empty_name_in_from_array(): void
    {
        $data = [
            'email' => 'user@example.com',
            'password' => 'password',
            'name' => ''
        ];

        $dto = AuthDTO::fromArray($data);

        $this->assertEquals('user@example.com', $dto->email->value());
        $this->assertEquals('password', $dto->password);
        $this->assertEquals('', $dto->name);
        $this->assertTrue($dto->isForRegistration()); // empty string is still truthy for name existence
    }

    public function test_it_handles_various_valid_email_formats(): void
    {
        $validEmails = [
            'simple@example.com',
            'very.common@example.com',
            'test+tag@example.co.uk',
            'user-name@example-domain.com',
        ];

        foreach ($validEmails as $email) {
            $dto = AuthDTO::forLogin($email, 'password');
            $this->assertEquals($email, $dto->email->value());
        }
    }
}
