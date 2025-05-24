<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\Admin;
use App\Enums\ProfileStatut;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
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
            'statut' => fake()->randomElement(ProfileStatut::cases()),
            'admin_id' => Admin::factory(),
        ];
    }
}
