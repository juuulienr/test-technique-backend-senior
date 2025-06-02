<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\DTOs;

use App\Domain\DTOs\CreateCommentDTO;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class CreateCommentDTOTest extends TestCase
{
    public function test_it_creates_dto_with_valid_data(): void
    {
        $dto = new CreateCommentDTO(
            contenu: 'Ceci est un commentaire valide',
            adminId: 1,
            profileId: 2
        );

        $this->assertEquals('Ceci est un commentaire valide', $dto->contenu);
        $this->assertEquals(1, $dto->adminId);
        $this->assertEquals(2, $dto->profileId);
    }

    public function test_it_creates_from_array(): void
    {
        $data = [
            'contenu' => 'Un autre commentaire',
            'admin_id' => 3,
            'profile_id' => 4
        ];

        $dto = CreateCommentDTO::fromArray($data);

        $this->assertEquals('Un autre commentaire', $dto->contenu);
        $this->assertEquals(3, $dto->adminId);
        $this->assertEquals(4, $dto->profileId);
    }

    public function test_it_converts_to_array(): void
    {
        $dto = new CreateCommentDTO(
            contenu: 'Commentaire de test',
            adminId: 5,
            profileId: 6
        );

        $expected = [
            'contenu' => 'Commentaire de test',
            'admin_id' => 5,
            'profile_id' => 6,
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_it_throws_exception_for_empty_content(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le contenu du commentaire ne peut pas être vide');

        new CreateCommentDTO(
            contenu: '',
            adminId: 1,
            profileId: 2
        );
    }

    public function test_it_throws_exception_for_whitespace_only_content(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le contenu du commentaire ne peut pas être vide');

        new CreateCommentDTO(
            contenu: '   ',
            adminId: 1,
            profileId: 2
        );
    }

    public function test_it_throws_exception_for_too_long_content(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Le contenu du commentaire ne peut pas dépasser 5000 caractères');

        new CreateCommentDTO(
            contenu: str_repeat('a', 5001),
            adminId: 1,
            profileId: 2
        );
    }

    public function test_it_accepts_content_at_max_length(): void
    {
        $maxContent = str_repeat('a', 5000);

        $dto = new CreateCommentDTO(
            contenu: $maxContent,
            adminId: 1,
            profileId: 2
        );

        $this->assertEquals($maxContent, $dto->contenu);
    }

    public function test_it_handles_multiline_content(): void
    {
        $multilineContent = "Première ligne\nDeuxième ligne\nTroisième ligne";

        $dto = new CreateCommentDTO(
            contenu: $multilineContent,
            adminId: 1,
            profileId: 2
        );

        $this->assertEquals($multilineContent, $dto->contenu);
    }

    public function test_it_handles_special_characters(): void
    {
        $specialContent = "Commentaire avec caractères spéciaux: àéêèçùû & @#$%";

        $dto = new CreateCommentDTO(
            contenu: $specialContent,
            adminId: 1,
            profileId: 2
        );

        $this->assertEquals($specialContent, $dto->contenu);
    }
}
