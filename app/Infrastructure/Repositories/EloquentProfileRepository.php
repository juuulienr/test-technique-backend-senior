<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\ProfileRepositoryInterface;
use App\Enums\ProfileStatut;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Collection;

final class EloquentProfileRepository implements ProfileRepositoryInterface
{
    public function findById(int $id): ?Profile
    {
        return Profile::find($id);
    }

    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    public function update(Profile $profile, array $data): Profile
    {
        $profile->update($data);
        return $profile->fresh();
    }

    public function delete(Profile $profile): void
    {
        $profile->delete();
    }

    public function findByStatus(ProfileStatut $statut): Collection
    {
        return Profile::where('statut', $statut->value)->get();
    }

    public function findActiveProfiles(): Collection
    {
        return Profile::where('statut', ProfileStatut::ACTIF->value)->get();
    }

    public function findByAdminId(int $adminId): Collection
    {
        return Profile::where('admin_id', $adminId)->get();
    }

    public function countByAdminId(int $adminId): int
    {
        return Profile::where('admin_id', $adminId)->count();
    }

    public function findWithComments(int $profileId): ?Profile
    {
        return Profile::with('comments')->find($profileId);
    }
}
