<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class MovieController extends ApiController
{
    use AuthorizesRequests;

    public function index(): JsonResponse
    {
        $movies = Movie::withAvg('reviews', 'rating')->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $movies,
        ]);
    }

    public function show(string $movie): JsonResponse
    {
        $movie = Movie::find($movie);

        if (! $movie) {
            return response()->json([
                'status' => 'error',
                'message' => 'Movie not found',
            ], 404);
        }

        $movie->load('genres', 'reviews')->loadAvg('reviews', 'rating');

        return response()->json([
            'status' => 'success',
            'data' => $movie,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'required|date_format:Y-m-d',
            'director' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'integer|exists:genres,id',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $genreIds = $validated['genres'] ?? [];
        unset($validated['genres']);

        $movie = $user->movies()->create($validated);

        if (! empty($genreIds)) {
            $movie->genres()->attach($genreIds);
        }

        $movie->load('genres');

        return response()->json([
            'status' => 'success',
            'data' => $movie,
        ], 201);
    }

    public function update(Request $request, Movie $movie): JsonResponse
    {
        $response = Gate::inspect('update', $movie);

        if (! $response->allowed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this movie.',
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'required|date_format:Y-m-d',
            'director' => 'nullable|string|max:255',
            'genres' => 'nullable|array',
            'genres.*' => 'integer|exists:genres,id',
        ]);

        $genreIds = $validated['genres'] ?? [];
        unset($validated['genres']);

        $movie->update($validated);

        if ($request->has('genres')) {
            $movie->genres()->sync($genreIds);
        }

        $movie->load('genres');

        return response()->json([
            'status' => 'success',
            'data' => $movie,
        ]);
    }

    public function destroy(Movie $movie): JsonResponse
    {
        $response = Gate::inspect('delete', $movie);

        if (! $response->allowed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not own this movie.',
            ], 403);
        }

        $movie->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Movie deleted successfully',
        ]);
    }
}
