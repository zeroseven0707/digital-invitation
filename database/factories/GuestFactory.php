<?php

namespace Database\Factories;

use App\Models\Guest;
use App\Models\Invitation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Guest>
 */
class GuestFactory extends Factory
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
            'name' => fake()->name(),
            'category' => fake()->randomElement([
                Guest::CATEGORY_FAMILY,
                Guest::CATEGORY_FRIEND,
                Guest::CATEGORY_COLLEAGUE,
            ]),
            'whatsapp_number' => fake()->optional(0.7)->numerify('628##########'), // 70% chance to have WhatsApp
        ];
    }

    /**
     * Indicate that the guest is family.
     */
    public function family(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => Guest::CATEGORY_FAMILY,
        ]);
    }

    /**
     * Indicate that the guest is a friend.
     */
    public function friend(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => Guest::CATEGORY_FRIEND,
        ]);
    }

    /**
     * Indicate that the guest is a colleague.
     */
    public function colleague(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => Guest::CATEGORY_COLLEAGUE,
        ]);
    }
}
