<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends ApiController
{
    public function index()
    {
        $movies = Movie::paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $movies,
        ]);
    }

    public function show(Movie $movie)
    {
        $movie->load('genres', 'reviews')->loadAvg('reviews', 'rating');

        return response()->json([
            'status' => 'success',
            'data' => $movie,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'required|date_format:Y-m-d', // postman data -> 2023-10-15
            'director' => 'nullable|string|max:255',
        ]);

        $movie = Movie::create(array_merge(
            $validated, ['user_id' => $request->user()->id]
        ));

        return response()->json([
            'status' => 'success',
            'data' => $movie,
        ], 201);
    }

    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'release_date' => 'required|date_format:Y-m-d', // postman data -> 2023-10-15
            'director' => 'nullable|string|max:255',
        ]);

        $movie->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $movie,
        ]);
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Movie deleted successfully',
        ]);
    }
}
