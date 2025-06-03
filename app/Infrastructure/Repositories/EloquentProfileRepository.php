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

    /**
     * @param array<string, mixed> $data
     */
    public function create(array $data): Profile
    {
        return Profile::create($data);
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(Profile $profile, array $data): Profile
    {
        $profile->update($data);
        $freshProfile = $profile->fresh();
        
        if ($freshProfile === null) {
            throw new \RuntimeException('Le profil n\'existe plus après la mise à jour');
        }
        
        return $freshProfile;
    }

    public function delete(Profile $profile): void
    {
        $profile->delete();
    }

    /**
     * @return Collection<int, Profile>
     */
    public function findByStatus(ProfileStatut $statut): Collection
    {
        return Profile::where('statut', $statut->value)->get();
    }

    /**
     * @return Collection<int, Profile>
     */
    public function findActiveProfiles(): Collection
    {
        return Profile::where('statut', ProfileStatut::ACTIF->value)->get();
    }

    /**
     * @return Collection<int, Profile>
     */
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
