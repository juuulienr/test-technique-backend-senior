<?php

namespace App\Http\Resources;

use App\Models\Profile;
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
     *     created_at: \Carbon\Carbon|null,
     *     updated_at: \Carbon\Carbon|null,
     *     statut?: \App\Enums\ProfileStatut
     * }
     */
    public function toArray(Request $request): array
    {
        $data = [
          'id' => $this->resource->id,
          'nom' => $this->resource->nom,
          'prenom' => $this->resource->prenom,
          'image_url' => $this->resource->image ? asset('storage/' . $this->resource->image) : null,
          'admin_id' => $this->resource->admin_id,
          'created_at' => $this->resource->created_at,
          'updated_at' => $this->resource->updated_at,
        ];

        if ($request->user() && $request->user() instanceof \App\Models\Admin) {
            $data['statut'] = $this->resource->statut;
        }

        return $data;
    }
}
