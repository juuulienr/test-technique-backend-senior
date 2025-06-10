<?php

namespace App\UI\Http\Resources;

use App\Domain\Entities\Profile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read Profile $resource
 */
class ProfileResource extends JsonResource
{
    /**
     * @return array{
     *     id: int,
     *     nom: string,
     *     prenom: string,
     *     image_url: string|null,
     *     admin_id: int,
     *     created_at: \DateTimeImmutable,
     *     updated_at: \DateTimeImmutable,
     *     statut?: \App\Domain\ValueObjects\ProfileStatut
     * }
     */
    public function toArray(Request $request): array
    {
        /** @var Profile $profile */
        $profile = $this->resource;

        $data = [
          'id' => $profile->getId()->getValue(),
          'nom' => $profile->getName()->nom(),
          'prenom' => $profile->getName()->prenom(),
          'image_url' => $profile->getImagePath() ? asset('storage/' . $profile->getImagePath()) : null,
          'admin_id' => $profile->getAdminId()->getValue(),
          'created_at' => $profile->getCreatedAt(),
          'updated_at' => $profile->getUpdatedAt(),
        ];

        if ($request->user() && $request->user() instanceof \App\Infrastructure\Models\Admin) {
            $data['statut'] = $profile->getStatut();
        }

        return $data;
    }
}
