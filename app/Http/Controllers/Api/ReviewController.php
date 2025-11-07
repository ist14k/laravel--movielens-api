<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Movie $movie): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'review' => 'nullable|string',
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $review = $movie->reviews()->create(array_merge(
            $validated, ['user_id' => $user->id]
        ));

        return response()->json([
            'status' => 'success',
            'data' => $review,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movie $movie, Review $review): JsonResponse
    {
        if ($review->movie_id !== $movie->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review does not belong to the specified movie',
            ], 404);
        }

        $review->load('user', 'movie');

        return response()->json([
            'status' => 'success',
            'data' => $review,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movie $movie, Review $review): JsonResponse
    {
        if ($review->movie_id !== $movie->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review does not belong to the specified movie',
            ], 404);
        }

        $validated = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'title' => 'sometimes|nullable|string|max:255',
            'review' => 'sometimes|nullable|string',
        ]);

        $review->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $review,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movie $movie, Review $review): JsonResponse
    {
        if ($review->movie_id !== $movie->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Review does not belong to the specified movie',
            ], 404);
        }

        $review->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Review deleted successfully',
        ]);
    }
}
