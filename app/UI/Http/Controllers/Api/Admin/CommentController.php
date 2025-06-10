<?php

namespace App\UI\Http\Controllers\Api\Admin;

use App\Application\DTOs\CreateCommentDTO;
use App\Application\Services\CommentApplicationService;
use App\UI\Http\Controllers\Controller;
use App\UI\Http\Requests\Comment\StoreCommentRequest;
use App\UI\Http\Responses\ApiResponse;
use App\Infrastructure\Models\Profile;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Models\Admin;

class CommentController extends Controller
{
    public function __construct(private CommentApplicationService $commentApplicationService)
    {
    }

    public function store(StoreCommentRequest $request, Profile $profile): JsonResponse
    {
        /** @var Admin $user */
        $user = $request->user();

        $createCommentDTO = new CreateCommentDTO(
            contenu: $request->validated('contenu'),
            adminId: $user->id,
            profileId: $profile->id
        );

        try {
            $comment = $this->commentApplicationService->createComment($createCommentDTO);
            return ApiResponse::created($comment, 'Commentaire créé avec succès');
        } catch (\InvalidArgumentException $e) {
            return ApiResponse::error($e->getMessage(), 403);
        }
    }
}
