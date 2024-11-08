<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NumGraduate>
 */
class NumGraduateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quantity' => $this->faker->numberBetween(1, 150),
            'year' => $this->faker->year(),
            'campus_id' => $this->faker->numberBetween(1, 10),
            'career_id' => $this->faker->numberBetween(1, 355),
        ];
    }
}
