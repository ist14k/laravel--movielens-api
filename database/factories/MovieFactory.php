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
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'release_date' => $this->faker->date(),
            'director' => $this->faker->name(),
            'poster_img_url' => $this->faker->imageUrl(200, 300, 'movies', true),
            'cover_img_url' => $this->faker->imageUrl(800, 400, 'movies', true),
        ];
    }
}
