<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\AdminRepositoryInterface;
use App\Domain\Entities\Admin as DomainAdmin;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\Email;
use App\Infrastructure\Mappers\AdminMapper;
use App\Infrastructure\Models\Admin as EloquentAdmin;

final class EloquentAdminRepository implements AdminRepositoryInterface
{
    public function findByEmail(Email $email): ?DomainAdmin
    {
        $eloquentAdmin = EloquentAdmin::where('email', $email->value())->first();
        
        return $eloquentAdmin ? AdminMapper::toDomain($eloquentAdmin) : null;
    }

    public function save(DomainAdmin $admin): DomainAdmin
    {
        $data = AdminMapper::toEloquentData($admin);
        
        if ($admin->getId()->getValue() > 1) {
            // Mise à jour (ID > 1 car 1 est utilisé pour les nouveaux)
            $eloquentAdmin = EloquentAdmin::findOrFail($admin->getId()->getValue());
            $eloquentAdmin->update($data);
        } else {
            // Création
            $eloquentAdmin = EloquentAdmin::create($data);
        }
        
        return AdminMapper::toDomain($eloquentAdmin);
    }

    public function emailExists(Email $email): bool
    {
        return EloquentAdmin::where('email', $email->value())->exists();
    }

    public function findById(AdminId $id): ?DomainAdmin
    {
        $eloquentAdmin = EloquentAdmin::find($id->getValue());
        
        return $eloquentAdmin ? AdminMapper::toDomain($eloquentAdmin) : null;
    }


}
