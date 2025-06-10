<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Profile as ProfileEntity;
use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Domain\ValueObjects\ProfileId;
use App\Domain\ValueObjects\AdminId;
use App\Infrastructure\Mappers\ProfileMapper;
use App\Infrastructure\Models\Profile as ProfileModel;
use App\Domain\ValueObjects\ProfileStatut;

final class EloquentProfileRepository implements ProfileRepositoryInterface
{
    public function findById(ProfileId $id): ?ProfileEntity
    {
        $model = ProfileModel::find($id->getValue());
        
        return $model ? ProfileMapper::toDomain($model) : null;
    }

    public function save(ProfileEntity $profile): ProfileEntity
    {
        // Si c'est un nouveau profil (ID = 0)
        if ($profile->getId()->getValue() === 0) {
            $model = ProfileMapper::toModel($profile);
            $model->save();
            
            // Retourner l'entité avec le nouvel ID
            return $profile->withId(new ProfileId($model->id));
        }

        // Mise à jour d'un profil existant
        $model = ProfileModel::findOrFail($profile->getId()->getValue());
        ProfileMapper::updateModel($model, $profile);
        $model->save();

        return ProfileMapper::toDomain($model);
    }

    public function delete(ProfileEntity $profile): void
    {
        $model = ProfileModel::findOrFail($profile->getId()->getValue());
        $model->delete();
    }

    /**
     * @return ProfileEntity[]
     */
    public function findByStatus(ProfileStatut $statut): array
    {
        $models = ProfileModel::where('statut', $statut->value)->get();
        
        return ProfileMapper::toDomainArray($models);
    }

    /**
     * @return ProfileEntity[]
     */
    public function findActiveProfiles(): array
    {
        $models = ProfileModel::where('statut', ProfileStatut::ACTIF->value)->get();
        
        return ProfileMapper::toDomainArray($models);
    }

    /**
     * @return ProfileEntity[]
     */
    public function findByAdminId(AdminId $adminId): array
    {
        $models = ProfileModel::where('admin_id', $adminId->getValue())->get();
        
        return ProfileMapper::toDomainArray($models);
    }

    public function countByAdminId(AdminId $adminId): int
    {
        return ProfileModel::where('admin_id', $adminId->getValue())->count();
    }

    public function exists(ProfileId $id): bool
    {
        return ProfileModel::where('id', $id->getValue())->exists();
    }
}
