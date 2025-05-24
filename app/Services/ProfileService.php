<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

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

    /**
     * @param array{
     *     nom?: string,
     *     prenom?: string,
     *     statut?: string,
     *     image?: string|null
     * } $data
     */
    public function updateProfile(Profile $profile, array $data, ?UploadedFile $image): Profile
    {
        if ($image) {
            if ($profile->image) {
                Storage::disk('public')->delete($profile->image);
            }
            $data['image'] = $image->store('images', 'public');
        }

        $profile->update($data);

        return $profile;
    }

    public function deleteProfile(Profile $profile): void
    {
        if ($profile->image) {
            Storage::disk('public')->delete($profile->image);
        }
        $profile->delete();
    }

}
