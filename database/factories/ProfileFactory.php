<?php

namespace Database\Factories;

use App\Infrastructure\Models\Profile;
use App\Infrastructure\Models\Admin;
use App\Domain\ValueObjects\ProfileStatut;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Infrastructure\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nom' => fake()->lastName(),
            'prenom' => fake()->firstName(),
            'image' => fake()->imageUrl(),
            'statut' => fake()->randomElement(array_map(fn ($e) => $e->value, ProfileStatut::cases())),
            'admin_id' => Admin::factory(),
        ];
    }
}
