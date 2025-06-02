<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\CommentRepositoryInterface;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Collection;

final class EloquentCommentRepository implements CommentRepositoryInterface
{
    public function findById(int $id): ?Comment
    {
        return Comment::find($id);
    }

    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function delete(Comment $comment): void
    {
        $comment->delete();
    }

    public function hasAdminCommentedProfile(Admin $admin, Profile $profile): bool
    {
        return Comment::where('admin_id', $admin->id)
                     ->where('profile_id', $profile->id)
                     ->exists();
    }

    public function findByProfileId(int $profileId): Collection
    {
        return Comment::where('profile_id', $profileId)
                     ->with('admin')
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    public function findByAdminId(int $adminId): Collection
    {
        return Comment::where('admin_id', $adminId)
                     ->with('profile')
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    public function deleteByProfileId(int $profileId): int
    {
        return Comment::where('profile_id', $profileId)->delete();
    }

    public function countByProfileId(int $profileId): int
    {
        return Comment::where('profile_id', $profileId)->count();
    }

    public function findRecent(int $limit = 10): Collection
    {
        return Comment::with(['admin', 'profile'])
                     ->orderBy('created_at', 'desc')
                     ->limit($limit)
                     ->get();
    }
}
