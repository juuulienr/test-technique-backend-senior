<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Service de gestion des images - service d'infrastructure
 * Responsable de l'upload, suppression et manipulation des fichiers images
 */
final class ImageService
{
    /**
     * Upload une image et retourne le chemin de stockage
     */
    public function upload(UploadedFile $image, string $directory = 'images'): string
    {
        $path = $image->store($directory, 'public');

        if ($path === false) {
            throw new \RuntimeException('Failed to upload image');
        }

        return $path;
    }

    /**
     * Supprime une image du stockage
     */
    public function delete(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        return Storage::disk('public')->delete($imagePath);
    }

    /**
     * Remplace une ancienne image par une nouvelle
     */
    public function replace(?string $oldImagePath, UploadedFile $newImage, string $directory = 'images'): string
    {
        // Supprimer l'ancienne image si elle existe
        if ($oldImagePath) {
            $this->delete($oldImagePath);
        }

        // Upload la nouvelle image
        return $this->upload($newImage, $directory);
    }

    /**
     * Vérifie si une image existe dans le stockage
     */
    public function exists(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        return Storage::disk('public')->exists($imagePath);
    }

    /**
     * Retourne l'URL publique d'une image
     */
    public function getUrl(?string $imagePath): ?string
    {
        if (!$imagePath) {
            return null;
        }

        return Storage::url($imagePath);
    }
}
