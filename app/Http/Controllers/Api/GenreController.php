<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index(): JsonResponse
    {
        $genres = Genre::all();

        return response()->json([
            'status' => 'success',
            'data' => $genres,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:genres,name',
            'description' => 'nullable|string',
        ]);

        $genre = Genre::create($validated);

        return response()->json([
            'status' => 'success',
            'data' => $genre,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $genre = Genre::find($id);

        if (! $genre) {
            return response()->json([
                'status' => 'error',
                'message' => 'Genre not found',
            ], 404);
        }

        $movies = $genre->movies()->paginate(12);

        return response()->json([
            'status' => 'success',
            'data' => [
                'genre' => $genre,
                'movies' => $movies,
            ],
        ]);
    }

    public function update(Request $request, Genre $genre): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:genres,name',
            'description' => 'sometimes|nullable|string',
        ]);

        $genre->update($validated);

        return response()->json([
            'status' => 'success',
            'data' => $genre,
        ]);
    }

    public function destroy(Genre $genre): JsonResponse
    {
        $genre->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Genre deleted successfully',
        ]);
    }
}
