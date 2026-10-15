<?php

namespace Database\Factories;

use App\Models\Experience;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Experience>
 */
class ExperienceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => fake()->jobTitle(),
            'company' => fake()->company(),
            'period' => '2024 — Sekarang',
            'description' => fake()->paragraph(),
            'skills' => ['Laravel', 'Tailwind CSS', 'PHP'],
            'sort_order' => 0,
        ];
    }
}
