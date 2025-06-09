<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Admin;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\Email;

/**
 * Interface Repository pour les administrateurs
 * Port primaire dans l'architecture hexagonale
 */
interface AdminRepositoryInterface
{
    /**
     * Trouve un admin par son email
     */
    public function findByEmail(Email $email): ?Admin;

    /**
     * Sauvegarde un admin
     */
    public function save(Admin $admin): Admin;

    /**
     * Vérifie si un email existe déjà
     */
    public function emailExists(Email $email): bool;

    /**
     * Trouve un admin par son ID
     */
    public function findById(AdminId $id): ?Admin;


}
