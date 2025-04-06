<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoomFactory extends Factory
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
			'description' => fake()->text(100),
			'price_per_night' => fake()->randomFloat(2, 50, 500),
			'is_available' => fake()->boolean(),
		];
    }
}
