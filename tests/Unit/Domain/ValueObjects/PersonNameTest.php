<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObjects;

use App\Domain\ValueObjects\PersonName;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class PersonNameTest extends TestCase
{
    public function test_it_creates_valid_person_name(): void
    {
        $personName = new PersonName('Dupont', 'Jean');

        $this->assertEquals('Dupont', $personName->nom());
        $this->assertEquals('Jean', $personName->prenom());
        $this->assertEquals('Jean Dupont', $personName->fullName());
    }

    public function test_it_handles_special_characters(): void
    {
        $personName = new PersonName("O'Connor", "Jean-Pierre");

        $this->assertEquals("O'Connor", $personName->nom());
        $this->assertEquals("Jean-Pierre", $personName->prenom());
    }

    public function test_it_throws_exception_for_empty_nom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le nom ne peut pas être vide');

        new PersonName('', 'Jean');
    }

    public function test_it_throws_exception_for_empty_prenom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le prénom ne peut pas être vide');

        new PersonName('Dupont', '');
    }

    public function test_it_throws_exception_for_whitespace_only_nom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le nom ne peut pas être vide');

        new PersonName('   ', 'Jean');
    }

    public function test_it_throws_exception_for_too_long_nom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le nom ne peut pas dépasser 255 caractères');

        new PersonName(str_repeat('a', 256), 'Jean');
    }

    public function test_it_throws_exception_for_invalid_characters_in_nom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le nom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets');

        new PersonName('Dupont123', 'Jean');
    }

    public function test_it_throws_exception_for_invalid_characters_in_prenom(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le prénom ne peut contenir que des lettres, des espaces, des apostrophes et des tirets');

        new PersonName('Dupont', 'Jean@');
    }

    public function test_equality(): void
    {
        $name1 = new PersonName('Dupont', 'Jean');
        $name2 = new PersonName('Dupont', 'Jean');
        $name3 = new PersonName('Martin', 'Jean');

        $this->assertTrue($name1->equals($name2));
        $this->assertFalse($name1->equals($name3));
    }

    public function test_to_array(): void
    {
        $personName = new PersonName('Dupont', 'Jean');

        $expected = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
        ];

        $this->assertEquals($expected, $personName->toArray());
    }
}
