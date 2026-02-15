<?php

namespace Database\Factories;

use App\Models\Template;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invitation>
 */
class InvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'template_id' => Template::factory(),
            'unique_url' => null,
            'status' => 'draft',
            'bride_name' => fake()->firstName('female'),
            'bride_father_name' => fake()->name('male'),
            'bride_mother_name' => fake()->name('female'),
            'groom_name' => fake()->firstName('male'),
            'groom_father_name' => fake()->name('male'),
            'groom_mother_name' => fake()->name('female'),
            'akad_date' => fake()->dateTimeBetween('now', '+2 years'),
            'akad_time_start' => '09:00',
            'akad_time_end' => '11:00',
            'akad_location' => fake()->address(),
            'reception_date' => fake()->dateTimeBetween('now', '+2 years'),
            'reception_time_start' => '18:00',
            'reception_time_end' => '21:00',
            'reception_location' => fake()->address(),
            'full_address' => fake()->address(),
            'google_maps_url' => 'https://maps.google.com/?q=' . fake()->latitude() . ',' . fake()->longitude(),
            'music_url' => null,
        ];
    }

    /**
     * Indicate that the invitation is published.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'unique_url' => fake()->unique()->slug(),
        ]);
    }

    /**
     * Indicate that the invitation is unpublished.
     */
    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'unpublished',
        ]);
    }
}
