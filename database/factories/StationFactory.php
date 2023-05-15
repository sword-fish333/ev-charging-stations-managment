<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Station>
 */
class StationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'latitude' => fake()->latitude(49.9, 59.4),
            'longitude' => fake()->longitude(-7.5, 1.7),
            'company_id'=>Company::inRandomOrder()->first()?->id,
        ];
    }
}
