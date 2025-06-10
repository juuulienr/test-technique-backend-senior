<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\DTOs;

use App\Application\DTOs\CreateProfileDTO;
use App\Domain\ValueObjects\PersonName;
use App\Domain\ValueObjects\ProfileStatut;
use PHPUnit\Framework\TestCase;

class CreateProfileDTOTest extends TestCase
{
    public function test_it_creates_dto_with_constructor(): void
    {
        $name = new PersonName('Dupont', 'Jean');
        $dto = new CreateProfileDTO(
            name: $name,
            statut: ProfileStatut::ACTIF,
            imagePath: 'images/photo.jpg',
            adminId: 1
        );

        $this->assertEquals($name, $dto->name);
        $this->assertEquals(ProfileStatut::ACTIF, $dto->statut);
        $this->assertEquals('images/photo.jpg', $dto->imagePath);
        $this->assertEquals(1, $dto->adminId);
    }

    public function test_it_creates_from_array(): void
    {
        $data = [
            'nom' => 'Martin',
            'prenom' => 'Pierre',
            'statut' => 'actif',
            'image_path' => 'images/avatar.png',
            'admin_id' => 2
        ];

        $dto = CreateProfileDTO::fromArray($data);

        $this->assertEquals('Martin', $dto->name->nom());
        $this->assertEquals('Pierre', $dto->name->prenom());
        $this->assertEquals(ProfileStatut::ACTIF, $dto->statut);
        $this->assertEquals('images/avatar.png', $dto->imagePath);
        $this->assertEquals(2, $dto->adminId);
    }

    public function test_it_converts_to_array(): void
    {
        $name = new PersonName('Durand', 'Marie');
        $dto = new CreateProfileDTO(
            name: $name,
            statut: ProfileStatut::EN_ATTENTE,
            imagePath: 'images/profile.jpg',
            adminId: 3
        );

        $expected = [
            'nom' => 'Durand',
            'prenom' => 'Marie',
            'statut' => 'en attente',
            'image' => 'images/profile.jpg',
            'admin_id' => 3,
        ];

        $this->assertEquals($expected, $dto->toArray());
    }

    public function test_from_array_validates_person_name(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $data = [
            'nom' => '', // nom vide
            'prenom' => 'Jean',
            'statut' => 'actif',
            'image_path' => 'images/photo.jpg',
            'admin_id' => 1
        ];

        CreateProfileDTO::fromArray($data);
    }

    public function test_from_array_validates_statut(): void
    {
        $this->expectException(\ValueError::class);

        $data = [
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'statut' => 'statut_invalide',
            'image_path' => 'images/photo.jpg',
            'admin_id' => 1
        ];

        CreateProfileDTO::fromArray($data);
    }
}
