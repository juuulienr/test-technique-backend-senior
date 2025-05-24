<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Http\UploadedFile;

class ProfileService
{
    /**
     * @param array{
     *     nom: string,
     *     prenom: string,
     *     statut: string
     * } $data
     */
    public function createProfile(array $data, ?UploadedFile $image, int $adminId): Profile
    {
        $path = $image?->store('images', 'public');

        return Profile::create([
          'nom' => $data['nom'],
          'prenom' => $data['prenom'],
          'image' => $path,
          'statut' => $data['statut'],
          'admin_id' => $adminId,
        ]);
    }
}
