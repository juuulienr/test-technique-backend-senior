<?php

namespace App\Services;

use App\Models\Profile;
use Illuminate\Http\UploadedFile;

class ProfileService
{
    public function __construct(private ImageService $imageService)
    {
    }

    /**
     * @param array{
     *     nom: string,
     *     prenom: string,
     *     statut: string
     * } $data
     */
    public function createProfile(array $data, UploadedFile $image, int $adminId): Profile
    {
        $imagePath = $this->imageService->upload($image);

        return Profile::create([
          'nom' => $data['nom'],
          'prenom' => $data['prenom'],
          'image' => $imagePath,
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
            $data['image'] = $this->imageService->replace($profile->image, $image);
        }

        $profile->update($data);

        return $profile;
    }

    public function deleteProfile(Profile $profile): void
    {
        $this->imageService->delete($profile->image);
        $profile->delete();
    }
}
