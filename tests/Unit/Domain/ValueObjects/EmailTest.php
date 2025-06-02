<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_it_creates_valid_email(): void
    {
        $email = new Email('test@example.com');

        $this->assertEquals('test@example.com', $email->value());
        $this->assertEquals('test@example.com', (string) $email);
    }

    public function test_it_extracts_domain(): void
    {
        $email = new Email('user@domain.com');

        $this->assertEquals('domain.com', $email->domain());
    }

    public function test_it_extracts_local_part(): void
    {
        $email = new Email('user@domain.com');

        $this->assertEquals('user', $email->local());
    }

    public function test_it_throws_exception_for_empty_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'email ne peut pas être vide');

        new Email('');
    }

    public function test_it_throws_exception_for_whitespace_only_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'email ne peut pas être vide');

        new Email('   ');
    }

    public function test_it_throws_exception_for_invalid_email_format(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Format d\'email invalide');

        new Email('invalid-email');
    }

    public function test_it_throws_exception_for_too_long_email(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('L\'email ne peut pas dépasser 255 caractères');

        // Créer un email de plus de 255 caractères avec un format valide
        $longLocalPart = str_repeat('a', 240);
        $longEmail = $longLocalPart . '@example.com'; // 240 + 12 = 252 caractères, encore valide
        $veryLongEmail = $longLocalPart . '@' . str_repeat('b', 20) . '.com'; // > 255 caractères

        new Email($veryLongEmail);
    }

    public function test_equality(): void
    {
        $email1 = new Email('test@example.com');
        $email2 = new Email('test@example.com');
        $email3 = new Email('other@example.com');

        $this->assertTrue($email1->equals($email2));
        $this->assertFalse($email1->equals($email3));
    }

    public function test_it_handles_various_valid_email_formats(): void
    {
        $validEmails = [
            'simple@example.com',
            'very.common@example.com',
            'test+tag@example.co.uk',
            'user-name@example-domain.com',
        ];

        foreach ($validEmails as $emailString) {
            $email = new Email($emailString);
            $this->assertEquals($emailString, $email->value());
        }
    }
}
