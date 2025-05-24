<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;

class CommentService
{
    public function hasAlreadyCommented(Admin $admin, Profile $profile): bool
    {
        return Comment::where('admin_id', $admin->id)
                      ->where('profile_id', $profile->id)
                      ->exists();
    }

    public function createComment(string $contenu, Admin $admin, Profile $profile): Comment
    {
        return Comment::create([
          'contenu' => $contenu,
          'admin_id' => $admin->id,
          'profile_id' => $profile->id,
        ]);
    }
}
