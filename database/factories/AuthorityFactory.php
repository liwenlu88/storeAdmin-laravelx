<?php

namespace Database\Factories;

use App\Models\Authority;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Authority>
 */
class AuthorityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'user_id' => $this->faker->numberBetween(1, 10),
            'menu_id' => json_decode(json_encode([
                1, 2, 3, 4, 5, 6, 7, 8, 9, 10
            ]))
        ];
    }
}