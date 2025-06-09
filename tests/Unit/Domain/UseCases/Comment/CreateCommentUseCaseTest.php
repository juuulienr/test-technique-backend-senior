<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\UseCases\Comment;

use App\Application\DTOs\CreateCommentDTO;
use App\Application\UseCases\Comment\CreateCommentUseCase;
use App\Domain\Entities\Comment;
use App\Domain\Repositories\CommentRepositoryInterface;
use App\Domain\ValueObjects\AdminId;
use App\Domain\ValueObjects\ProfileId;
use App\Models\Admin;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use InvalidArgumentException;
use Tests\TestCase;

class CreateCommentUseCaseTest extends TestCase
{
    use RefreshDatabase;

    private CreateCommentUseCase $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $commentRepository = $this->app->make(CommentRepositoryInterface::class);
        $this->useCase = new CreateCommentUseCase($commentRepository);
    }

    public function test_it_creates_comment_successfully(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();

        $createCommentDTO = new CreateCommentDTO(
            contenu: 'Excellent profil !',
            adminId: $admin->id,
            profileId: $profile->id
        );

        $comment = $this->useCase->execute($createCommentDTO);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertEquals('Excellent profil !', $comment->getContenu());
        $this->assertEquals($admin->id, $comment->getAdminId()->getValue());
        $this->assertEquals($profile->id, $comment->getProfileId()->getValue());

        $this->assertDatabaseHas('comments', [
            'contenu' => 'Excellent profil !',
            'admin_id' => $admin->id,
            'profile_id' => $profile->id,
        ]);
    }

    public function test_it_throws_exception_for_duplicate_comment(): void
    {
        $admin = Admin::factory()->create();
        $profile = Profile::factory()->create();

        // Créer un commentaire existant en base
        \App\Models\Comment::factory()->create([
            'admin_id' => $admin->id,
            'profile_id' => $profile->id,
        ]);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Vous avez déjà commenté ce profil');

        $createCommentDTO = new CreateCommentDTO(
            contenu: 'Nouveau commentaire',
            adminId: $admin->id,
            profileId: $profile->id
        );

        $this->useCase->execute($createCommentDTO);
    }



    public function test_it_allows_different_admins_to_comment_same_profile(): void
    {
        $admin1 = Admin::factory()->create();
        $admin2 = Admin::factory()->create();
        $profile = Profile::factory()->create();

        $dto1 = new CreateCommentDTO(
            contenu: 'Premier commentaire',
            adminId: $admin1->id,
            profileId: $profile->id
        );

        $dto2 = new CreateCommentDTO(
            contenu: 'Deuxième commentaire',
            adminId: $admin2->id,
            profileId: $profile->id
        );

        $comment1 = $this->useCase->execute($dto1);
        $comment2 = $this->useCase->execute($dto2);

        $this->assertNotEquals($comment1->getId()->getValue(), $comment2->getId()->getValue());
        $this->assertEquals(2, \App\Models\Comment::where('profile_id', $profile->id)->count());
    }

    public function test_it_allows_same_admin_to_comment_different_profiles(): void
    {
        $admin = Admin::factory()->create();
        $profile1 = Profile::factory()->create();
        $profile2 = Profile::factory()->create();

        $dto1 = new CreateCommentDTO(
            contenu: 'Commentaire profil 1',
            adminId: $admin->id,
            profileId: $profile1->id
        );

        $dto2 = new CreateCommentDTO(
            contenu: 'Commentaire profil 2',
            adminId: $admin->id,
            profileId: $profile2->id
        );

        $comment1 = $this->useCase->execute($dto1);
        $comment2 = $this->useCase->execute($dto2);

        $this->assertNotEquals($comment1->getId()->getValue(), $comment2->getId()->getValue());
        $this->assertEquals(2, \App\Models\Comment::where('admin_id', $admin->id)->count());
    }
}
