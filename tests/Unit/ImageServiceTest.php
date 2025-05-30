<?php

namespace Tests\Unit;

use App\Services\ImageService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    private ImageService $imageService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->imageService = new ImageService();
        Storage::fake('public');
    }

    public function test_it_uploads_an_image(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');

        $path = $this->imageService->upload($image);

        $this->assertStringStartsWith('images/', $path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    public function test_it_uploads_an_image_to_custom_directory(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');

        $path = $this->imageService->upload($image, 'custom');

        $this->assertStringStartsWith('custom/', $path);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    public function test_it_deletes_an_existing_image(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');
        $path = $this->imageService->upload($image);

        $this->assertTrue(Storage::disk('public')->exists($path));

        $result = $this->imageService->delete($path);

        $this->assertTrue($result);
        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    public function test_it_returns_false_when_deleting_null_path(): void
    {
        $result = $this->imageService->delete(null);

        $this->assertFalse($result);
    }

    public function test_it_returns_false_when_deleting_empty_path(): void
    {
        $result = $this->imageService->delete('');

        $this->assertFalse($result);
    }

    public function test_it_replaces_an_image(): void
    {
        $oldImage = UploadedFile::fake()->image('old.jpg');
        $newImage = UploadedFile::fake()->image('new.jpg');

        // Upload initial image
        $oldPath = $this->imageService->upload($oldImage);
        $this->assertTrue(Storage::disk('public')->exists($oldPath));

        // Replace with new image
        $newPath = $this->imageService->replace($oldPath, $newImage);

        $this->assertNotEquals($oldPath, $newPath);
        $this->assertFalse(Storage::disk('public')->exists($oldPath));
        $this->assertTrue(Storage::disk('public')->exists($newPath));
    }

    public function test_it_replaces_null_image_with_new_image(): void
    {
        $newImage = UploadedFile::fake()->image('new.jpg');

        $newPath = $this->imageService->replace(null, $newImage);

        $this->assertTrue(Storage::disk('public')->exists($newPath));
    }

    public function test_it_checks_if_image_exists(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');
        $path = $this->imageService->upload($image);

        $this->assertTrue($this->imageService->exists($path));
        $this->assertFalse($this->imageService->exists('non-existent.jpg'));
        $this->assertFalse($this->imageService->exists(null));
    }

    public function test_it_returns_image_url(): void
    {
        $image = UploadedFile::fake()->image('test.jpg');
        $path = $this->imageService->upload($image);

        $url = $this->imageService->getUrl($path);

        $this->assertNotNull($url);
        $this->assertStringContainsString('/storage/', $url);
        $this->assertStringContainsString($path, $url);
    }

    public function test_it_returns_null_for_null_path_url(): void
    {
        $url = $this->imageService->getUrl(null);

        $this->assertNull($url);
    }

    public function test_it_returns_null_for_empty_path_url(): void
    {
        $url = $this->imageService->getUrl('');

        $this->assertNull($url);
    }
} 