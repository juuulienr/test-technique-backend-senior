<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Profile as ProfileEntity;
use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\PersonName;
use App\Domain\ValueObjects\AdminId;
use App\Infrastructure\Models\Profile as ProfileModel;
use App\Domain\ValueObjects\ProfileStatut;
use DateTimeImmutable;
use Illuminate\Support\Carbon;

/**
 * Mapper pour convertir entre entité Profile et modèle Eloquent
 */
final class ProfileMapper
{
    /**
     * Convertit une entité Domain en modèle Eloquent
     */
    public static function toModel(ProfileEntity $entity): ProfileModel
    {
        $model = new ProfileModel();
        
        // Si l'entité a un ID (pas nouveau), l'assigner
        if ($entity->getId()->getValue() > 0) {
            $model->id = $entity->getId()->getValue();
            $model->exists = true;
        }

        $model->nom = $entity->getName()->nom();
        $model->prenom = $entity->getName()->prenom();
        $model->statut = ProfileStatut::from($entity->getStatut()->value);
        $model->image = $entity->getImagePath();
        $model->admin_id = $entity->getAdminId()->getValue();
        $model->created_at = Carbon::createFromFormat('Y-m-d H:i:s', $entity->getCreatedAt()->format('Y-m-d H:i:s'));
        $model->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $entity->getUpdatedAt()->format('Y-m-d H:i:s'));

        return $model;
    }

    /**
     * Convertit un modèle Eloquent en entité Domain
     */
    public static function toDomain(ProfileModel $model): ProfileEntity
    {
        // Le cast Eloquent assure que $model->statut est toujours un ProfileStatut
        $statut = $model->statut;

        return ProfileEntity::fromPersistence(
            id: new ProfileId($model->id),
            name: new PersonName($model->nom, $model->prenom),
            statut: $statut,
            imagePath: $model->image,
            adminId: new AdminId($model->admin_id),
            createdAt: new DateTimeImmutable($model->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($model->updated_at->format('Y-m-d H:i:s'))
        );
    }

    /**
     * Convertit une collection de modèles en tableau d'entités
     * @param iterable<ProfileModel> $models
     * @return ProfileEntity[]
     */
    public static function toDomainArray(iterable $models): array
    {
        $entities = [];
        foreach ($models as $model) {
            $entities[] = self::toDomain($model);
        }
        return $entities;
    }

    /**
     * Met à jour un modèle existant avec les données d'une entité
     */
    public static function updateModel(ProfileModel $model, ProfileEntity $entity): ProfileModel
    {
        $model->nom = $entity->getName()->nom();
        $model->prenom = $entity->getName()->prenom();
        $model->statut = ProfileStatut::from($entity->getStatut()->value);
        $model->image = $entity->getImagePath();
        $model->admin_id = $entity->getAdminId()->getValue();
        $model->updated_at = Carbon::createFromFormat('Y-m-d H:i:s', $entity->getUpdatedAt()->format('Y-m-d H:i:s'));

        return $model;
    }
} 