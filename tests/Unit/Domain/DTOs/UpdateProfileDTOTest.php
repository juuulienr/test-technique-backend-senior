<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\DTOs;

use App\Domain\DTOs\UpdateProfileDTO;
use App\Domain\ValueObjects\PersonName;
use App\Enums\ProfileStatut;
use PHPUnit\Framework\TestCase;

class UpdateProfileDTOTest extends TestCase
{
    public function test_it_creates_empty_dto(): void
    {
        $dto = new UpdateProfileDTO();

        $this->assertNull($dto->name);
        $this->assertNull($dto->statut);
        $this->assertNull($dto->imagePath);
        $this->assertFalse($dto->hasChanges());
    }

    public function test_it_creates_dto_with_name_only(): void
    {
        $name = new PersonName('Dupont', 'Jean');
        $dto = new UpdateProfileDTO(name: $name);

        $this->assertEquals($name, $dto->name);
        $this->assertNull($dto->statut);
        $this->assertNull($dto->imagePath);
        $this->assertTrue($dto->hasChanges());
    }

    public function test_it_creates_dto_with_all_fields(): void
    {
        $name = new PersonName('Martin', 'Pierre');
        $dto = new UpdateProfileDTO(
            name: $name,
            statut: ProfileStatut::ACTIF,
            imagePath: 'images/new-photo.jpg'
        );

        $this->assertEquals($name, $dto->name);
        $this->assertEquals(ProfileStatut::ACTIF, $dto->statut);
        $this->assertEquals('images/new-photo.jpg', $dto->imagePath);
        $this->assertTrue($dto->hasChanges());
    }

    public function test_it_creates_from_array_with_partial_data(): void
    {
        $data = [
            'statut' => 'inactif',
            'image_path' => 'images/updated.png'
        ];

        $dto = UpdateProfileDTO::fromArray($data);

        $this->assertNull($dto->name);
        $this->assertEquals(ProfileStatut::INACTIF, $dto->statut);
        $this->assertEquals('images/updated.png', $dto->imagePath);
        $this->assertTrue($dto->hasChanges());
    }

    public function test_it_creates_from_array_with_complete_data(): void
    {
        $data = [
            'nom' => 'Durand',
            'prenom' => 'Marie',
            'statut' => 'en attente',
            'image_path' => 'images/complete.jpg'
        ];

        $dto = UpdateProfileDTO::fromArray($data);

        $this->assertNotNull($dto->name);
        $this->assertEquals('Durand', $dto->name->nom());
        $this->assertEquals('Marie', $dto->name->prenom());
        $this->assertEquals(ProfileStatut::EN_ATTENTE, $dto->statut);
        $this->assertEquals('images/complete.jpg', $dto->imagePath);
        $this->assertTrue($dto->hasChanges());
    }

    public function test_it_creates_from_array_with_empty_data(): void
    {
        $dto = UpdateProfileDTO::fromArray([]);

        $this->assertNull($dto->name);
        $this->assertNull($dto->statut);
        $this->assertNull($dto->imagePath);
        $this->assertFalse($dto->hasChanges());
    }

    public function test_it_converts_to_array_with_partial_data(): void
    {
        $dto = new UpdateProfileDTO(
            statut: ProfileStatut::ACTIF,
            imagePath: 'images/test.jpg'
        );

        $expected = [
            'statut' => 'actif',
            'image' => 'images/test.jpg',
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_it_converts_to_array_with_complete_data(): void
    {
        $name = new PersonName('Test', 'User');
        $dto = new UpdateProfileDTO(
            name: $name,
            statut: ProfileStatut::INACTIF,
            imagePath: 'images/full.png'
        );

        $expected = [
            'nom' => 'Test',
            'prenom' => 'User',
            'statut' => 'inactif',
            'image' => 'images/full.png',
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_it_converts_empty_dto_to_empty_array(): void
    {
        $dto = new UpdateProfileDTO();

        $this->assertEquals([], $dto->toArray());
    }

    public function test_from_array_handles_missing_name_parts(): void
    {
        // Seulement nom, pas de prénom
        $data = ['nom' => 'Dupont'];
        $dto = UpdateProfileDTO::fromArray($data);
        $this->assertNull($dto->name);

        // Seulement prénom, pas de nom
        $data = ['prenom' => 'Jean'];
        $dto = UpdateProfileDTO::fromArray($data);
        $this->assertNull($dto->name);
    }
}
