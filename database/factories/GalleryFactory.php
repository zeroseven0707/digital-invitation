<?php

namespace Database\Factories;

use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invitation_id' => Invitation::factory(),
            'photo_path' => 'invitations/' . fake()->numberBetween(1, 100) . '/gallery/' . fake()->uuid() . '.jpg',
            'order' => fake()->numberBetween(1, 10),
        ];
    }
}
