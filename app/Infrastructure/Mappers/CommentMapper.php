<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Entities\Comment as DomainComment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;
use App\Infrastructure\Models\Comment as EloquentComment;
use DateTimeImmutable;

/**
 * Mapper entre les entités de domaine Comment et les modèles Eloquent
 */
final class CommentMapper
{
    /**
     * Convertit un modèle Eloquent en entité de domaine
     */
    public static function toDomain(EloquentComment $eloquentComment): DomainComment
    {
        return new DomainComment(
            id: new CommentId($eloquentComment->id),
            contenu: $eloquentComment->contenu,
            adminId: new AdminId($eloquentComment->admin_id),
            profileId: new ProfileId($eloquentComment->profile_id),
            createdAt: new DateTimeImmutable($eloquentComment->created_at->format('Y-m-d H:i:s')),
            updatedAt: new DateTimeImmutable($eloquentComment->updated_at->format('Y-m-d H:i:s'))
        );
    }

    /**
     * Convertit une entité de domaine en données pour Eloquent
     * @return array<string, mixed>
     */
    public static function toEloquentData(DomainComment $domainComment): array
    {
        $data = [
            'contenu' => $domainComment->getContenu(),
            'admin_id' => $domainComment->getAdminId()->getValue(),
            'profile_id' => $domainComment->getProfileId()->getValue(),
        ];

        // Ajouter l'ID seulement s'il n'est pas 1 (nouveau commentaire utilise 1)
        if ($domainComment->getId()->getValue() > 1) {
            $data['id'] = $domainComment->getId()->getValue();
        }

        return $data;
    }

    /**
     * Convertit un tableau de modèles Eloquent en tableau d'entités de domaine
     * @param EloquentComment[] $eloquentComments
     * @return DomainComment[]
     */
    public static function toDomainArray(array $eloquentComments): array
    {
        return array_map(
            fn (EloquentComment $comment) => self::toDomain($comment),
            $eloquentComments
        );
    }
}
