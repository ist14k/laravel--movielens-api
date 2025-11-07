<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $genres = Genre::factory(10)->create();

        User::factory(10)->create()->each(function (User $user) use ($genres) {
            $movies = $user->movies()->saveMany(
                Movie::factory(random_int(1, 10))->create(['user_id' => $user->id])
            );

            // Attach genres to movies
            foreach ($movies as $movie) {
                $movie->genres()->attach(
                    $genres->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Create reviews for each movie
                Review::factory(rand(1, 5))->create([
                    'movie_id' => $movie->id,
                    'user_id' => $user->id,
                ]);
            }
        });

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->each(function (User $user) use ($genres) {
            $movies = $user->movies()->saveMany(
                Movie::factory(random_int(1, 10))->create(['user_id' => $user->id])
            );

            // Attach genres to movies
            foreach ($movies as $movie) {
                $movie->genres()->attach(
                    $genres->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Create reviews for each movie
                Review::factory(rand(1, 5))->create([
                    'movie_id' => $movie->id,
                    'user_id' => $user->id,
                ]);
            }
        });
    }
}
