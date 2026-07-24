<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteExerciseRequest;
use App\Http\Requests\StoreExerciseRequest;
use App\Http\Requests\UpdateExerciseRequest;
use App\Http\Resources\ExerciseCollection;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    use AuthorizesRequests;

    /**
     * GET /api/exercises
     * Display a listing of the resource.
     */
    public function index(Request $request): ExerciseCollection
    {
        $perPage = min($request->input('per_page', 15), 100);
        $exercises = $request->user()->exercises()->paginate($perPage);

        return new ExerciseCollection($exercises);
    }

    /**
     * POST /api/exercises
     * Store a newly created resource in storage.
     */
    public function store(StoreExerciseRequest $request): JsonResponse
    {
        // Validate the request
        $validated = $request->validated();

        $exercise = $request->user()->exercises()->create($validated);

        $exercise->load('personalTrainer');

        return response()->json([
            'message' => 'Esercizio creato con successo',
            'data' => new ExerciseResource($exercise),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exercise $exercise): JsonResponse
    {
        $this->authorize('view', $exercise);

        $exercise->load('personalTrainer');

        return response()->json([
            'data' => new ExerciseResource($exercise),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExerciseRequest $request, Exercise $exercise): JsonResponse
    {
        $exercise->update($request->validated());
        $exercise->load('personalTrainer');

        return response()->json([
            'message' => 'Esercizio aggiornato con successo',
            'data' => new ExerciseResource($exercise),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteExerciseRequest $request, Exercise $exercise): JsonResponse
    {
        $exercise->delete();

        return response()->json([
            'message' => 'Esercizio eliminato con successo',
        ], 200);
    }
}
