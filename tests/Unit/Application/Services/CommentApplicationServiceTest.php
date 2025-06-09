<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Services;

use App\Application\DTOs\CreateCommentDTO;
use App\Application\Services\CommentApplicationService;
use App\Application\UseCases\Comment\CreateCommentUseCase;
use App\Application\UseCases\Comment\GetCommentsByProfileUseCase;
use App\Domain\Entities\Comment;
use App\Domain\ValueObjects\ProfileId;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentApplicationServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommentApplicationService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->app->make(CommentApplicationService::class);
    }

    public function test_it_creates_comment_through_service(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();

        $createCommentDTO = new CreateCommentDTO(
            contenu: 'Excellent profil !',
            adminId: $admin->id,
            profileId: $profile->id
        );

        $comment = $this->service->createComment($createCommentDTO);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('Excellent profil !', $comment->getContenu());
        $this->assertEquals($admin->id, $comment->getAdminId()->getValue());
        $this->assertEquals($profile->id, $comment->getProfileId()->getValue());
    }

    public function test_it_gets_comments_by_profile(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();

        // Créer quelques commentaires en base
        \App\Models\Comment::factory()->create([
            'admin_id' => $admin->id,
            'profile_id' => $profile->id,
            'contenu' => 'Premier commentaire'
        ]);

        \App\Models\Comment::factory()->create([
            'admin_id' => $admin->id,
            'profile_id' => $profile->id,
            'contenu' => 'Deuxième commentaire'
        ]);

        $profileId = new ProfileId($profile->id);
        $comments = $this->service->getCommentsByProfile($profileId);

        $this->assertCount(2, $comments);
        $this->assertContainsOnlyInstancesOf(Comment::class, $comments);
    }
} 