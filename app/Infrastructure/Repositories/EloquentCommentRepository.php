<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\Entities\Comment as DomainComment;
use App\Domain\ValueObjects\CommentId;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;
use App\Infrastructure\Mappers\CommentMapper;
use App\Infrastructure\Models\Comment as EloquentComment;

final class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function findById(CommentId $id): ?DomainComment
    {
        $eloquentComment = EloquentComment::find($id->getValue());

        return $eloquentComment ? CommentMapper::toDomain($eloquentComment) : null;
    }

    public function save(DomainComment $comment): DomainComment
    {
        $data = CommentMapper::toEloquentData($comment);

        if ($comment->getId()->getValue() > 1) {
            // Mise à jour (ID > 1 car 1 est utilisé pour les nouveaux)
            $eloquentComment = EloquentComment::findOrFail($comment->getId()->getValue());
            $eloquentComment->update($data);
        } else {
            // Création
            $eloquentComment = EloquentComment::create($data);
        }

        return CommentMapper::toDomain($eloquentComment);
    }

    public function delete(CommentId $commentId): void
    {
        EloquentComment::where('id', $commentId->getValue())->delete();
    }

    public function hasAdminCommentedProfile(AdminId $adminId, ProfileId $profileId): bool
    {
        return EloquentComment::where('admin_id', $adminId->getValue())
                     ->where('profile_id', $profileId->getValue())
                     ->exists();
    }

    /**
     * @return DomainComment[]
     */
    public function findByProfileId(ProfileId $profileId): array
    {
        $eloquentComments = EloquentComment::where('profile_id', $profileId->getValue())
                     ->with('admin')
                     ->orderBy('created_at', 'desc')
                     ->get()
                     ->all();

        return CommentMapper::toDomainArray($eloquentComments);
    }

    /**
     * @return DomainComment[]
     */
    public function findByAdminId(AdminId $adminId): array
    {
        $eloquentComments = EloquentComment::where('admin_id', $adminId->getValue())
                     ->with('profile')
                     ->orderBy('created_at', 'desc')
                     ->get()
                     ->all();

        return CommentMapper::toDomainArray($eloquentComments);
    }

    public function deleteByProfileId(ProfileId $profileId): int
    {
        return EloquentComment::where('profile_id', $profileId->getValue())->delete();
    }

    public function countByProfileId(ProfileId $profileId): int
    {
        return EloquentComment::where('profile_id', $profileId->getValue())->count();
    }

    /**
     * @return DomainComment[]
     */
    public function findRecent(int $limit = 10): array
    {
        $eloquentComments = EloquentComment::with(['admin', 'profile'])
                     ->orderBy('created_at', 'desc')
                     ->limit($limit)
                     ->get()
                     ->all();

        return CommentMapper::toDomainArray($eloquentComments);
    }
}
