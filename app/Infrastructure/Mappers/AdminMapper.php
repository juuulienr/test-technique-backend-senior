<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Admin as DomainAdmin;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\Email;
use App\Infrastructure\Models\Admin as EloquentAdmin;
use DateTimeImmutable;

/**
 * Mapper entre les entités de domaine Admin et les modèles Eloquent
 */
final class AdminMapper
{
    /**
     * Convertit un modèle Eloquent en entité de domaine
     */
    public static function toDomain(EloquentAdmin $eloquentAdmin): DomainAdmin
    {
        return new DomainAdmin(
            id: new AdminId($eloquentAdmin->id),
            name: $eloquentAdmin->name,
            email: new Email($eloquentAdmin->email),
            hashedPassword: $eloquentAdmin->password,
            createdAt: new DateTimeImmutable($eloquentAdmin->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($eloquentAdmin->updated_at->format('Y-m-d H:i:s'))
        );
    }

    /**
     * Convertit une entité de domaine en données pour Eloquent
     * @return array<string, mixed>
     */
    public static function toEloquentData(DomainAdmin $domainAdmin): array
    {
        $data = [
            'name' => $domainAdmin->getName(),
            'email' => $domainAdmin->getEmail()->value(),
            'password' => $domainAdmin->getHashedPassword(),
        ];

        // Ajouter l'ID seulement s'il n'est pas 1 (nouveau admin utilise 1)
        if ($domainAdmin->getId()->getValue() > 1) {
            $data['id'] = $domainAdmin->getId()->getValue();
        }

        return $data;
    }
}
