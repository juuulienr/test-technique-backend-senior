<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\StoreCommentRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Profile;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use App\Models\Admin;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService)
    {
    }

    public function store(StoreCommentRequest $request, Profile $profile): JsonResponse
    {
        /** @var Admin $user */
        $user = $request->user();

        if ($this->commentService->hasAlreadyCommented($user, $profile)) {
            return ApiResponse::error('Vous avez déjà commenté ce profil.', 403);
        }

        $comment = $this->commentService->createComment(
            $request->validated('contenu'),
            $user,
            $profile
        );

        return ApiResponse::created($comment, 'Commentaire créé avec succès');
    }
}
