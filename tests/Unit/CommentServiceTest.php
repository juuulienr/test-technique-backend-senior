<?php

namespace Tests\Unit;

use App\Models\Admin;
use App\Models\Comment;
use App\Models\Profile;
use App\Services\CommentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    private CommentService $commentService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commentService = new CommentService();
    }

    public function test_it_creates_a_comment(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create(['admin_id' => $admin->id]);

        $comment = $this->commentService->createComment('Un test de commentaire', $admin, $profile);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertDatabaseHas('comments', [
          'contenu' => 'Un test de commentaire',
          'admin_id' => $admin->id,
          'profile_id' => $profile->id,
        ]);
    }

    public function test_it_detects_existing_comment(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create(['admin_id' => $admin->id]);

        Comment::factory()->create([
          'contenu' => 'Déjà existant',
          'admin_id' => $admin->id,
          'profile_id' => $profile->id,
        ]);

        $this->assertTrue(
            $this->commentService->hasAlreadyCommented($admin, $profile)
        );
    }

    public function test_it_detects_no_existing_comment(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create(['admin_id' => $admin->id]);

        $this->assertFalse(
            $this->commentService->hasAlreadyCommented($admin, $profile)
        );
    }
}
