<?php

declare(strict_types=1);

namespace App\Infrastructure\Adapters;

use App\Domain\Ports\ImageManagerPortInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Adaptateur Laravel pour la gestion d'images
 * Implémente ImageManagerPortInterface
 */
final class LaravelImageManagerAdapter implements ImageManagerPortInterface
{
    private const DISK = 'public';

    public function upload(mixed $imageFile, string $directory): string
    {
        if (!$imageFile instanceof UploadedFile) {
            throw new \InvalidArgumentException('Expected UploadedFile instance');
        }

        // Générer un nom unique pour l'image
        $filename = Str::uuid() . '.' . $imageFile->getClientOriginalExtension();
        $path = $directory . '/' . $filename;

        // Stocker l'image
        $storedPath = Storage::disk(self::DISK)->putFileAs($directory, $imageFile, $filename);

        if (!$storedPath) {
            throw new \RuntimeException('Failed to upload image');
        }

        return $storedPath;
    }

    public function delete(string $imagePath): bool
    {
        if (empty($imagePath)) {
            return false;
        }

        return Storage::disk(self::DISK)->delete($imagePath);
    }

    public function replace(?string $oldImagePath, mixed $newImageFile, string $directory): string
    {
        // Upload la nouvelle image
        $newPath = $this->upload($newImageFile, $directory);

        // Supprimer l'ancienne image si elle existe
        if ($oldImagePath) {
            $this->delete($oldImagePath);
        }

        return $newPath;
    }

    public function exists(string $imagePath): bool
    {
        if (empty($imagePath)) {
            return false;
        }

        return Storage::disk(self::DISK)->exists($imagePath);
    }

    public function getUrl(?string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        return asset('storage/' . $imagePath);
    }
} 