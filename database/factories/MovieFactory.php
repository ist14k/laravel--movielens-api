<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'release_date' => fake()->date(),
            'director' => fake()->name(),
            'poster_img_url' => fake()->imageUrl(200, 300, 'movies', true),
            'cover_img_url' => fake()->imageUrl(800, 400, 'movies', true),
        ];
    }
}
