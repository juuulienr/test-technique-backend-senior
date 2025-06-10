<?php

namespace Database\Factories;

use App\Infrastructure\Models\Admin;
use App\Infrastructure\Models\Comment;
use App\Infrastructure\Models\Profile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'contenu' => fake()->paragraph(),
            'admin_id' => Admin::factory(),
            'profile_id' => Profile::factory(),
        ];
    }
}
