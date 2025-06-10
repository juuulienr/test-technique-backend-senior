<?php

declare(strict_types=1);

namespace App\Domain\Ports;

/**
 * Port pour la gestion d'images
 * Interface pour découpler la logique métier de l'infrastructure
 */
interface ImageManagerPortInterface
{
    /**
     * Upload une image et retourne le chemin
     */
    public function upload(mixed $imageFile, string $directory): string;

    /**
     * Supprime une image
     */
    public function delete(string $imagePath): bool;

    /**
     * Remplace une image existante par une nouvelle
     */
    public function replace(?string $oldImagePath, mixed $newImageFile, string $directory): string;

    /**
     * Vérifie si une image existe
     */
    public function exists(string $imagePath): bool;

    /**
     * Retourne l'URL publique d'une image
     */
    public function getUrl(?string $imagePath): ?string;
} 