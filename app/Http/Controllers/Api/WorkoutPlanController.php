<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteWorkoutPlanRequest;
use App\Http\Requests\StoreWorkoutPlanRequest;
use App\Http\Requests\UpdateWorkoutPlanRequest;
use App\Http\Resources\WorkoutPlanCollection;
use App\Http\Resources\WorkoutPlanResource;
use App\Models\WorkoutPlan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkoutPlanController extends Controller
{

    use AuthorizesRequests;

    /**
     * GET /api/workout-plans
     * Display a listing of the resource.
     */
    public function index(Request $request): WorkoutPlanCollection
    {
        $perPage = min($request->input('per_page', 15), 100);
        $wplans = WorkoutPlan::query()
            // includi solo i workout plan assegnati ai clienti dell'utente
            ->whereIn('client_id', $request->user()->clients()->pluck('id'))
            ->with(['exercises', 'client'])
            ->withCount('exercises')
            ->paginate($perPage);

        return new WorkoutPlanCollection($wplans);
    }

    /**
     * POST /api/workout-plans
     * Store a newly created resource in storage.
     * Questa route viene utilizzata dall'utente (p.t.) per creare la scheda di allenamento al cliente selezionato.
     * La POST deve contenere anche gli esercizi da includere nella scheda, in modo da unificare la richiesta in una sola submit.
     */
    public function store(StoreWorkoutPlanRequest $request): JsonResponse
    {
        // Validate the request
        $validated = $request->validated();

        $workoutPlan = WorkoutPlan::create([
            'client_id' => $validated['client_id'],
        ]);

        foreach ($validated['exercises'] as $exercise) {
            $workoutPlan->exercises()->attach($exercise['id'], [
                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],
            ]);
        }
        $workoutPlan->load('exercises');


        return response()->json([
            'message' => 'Workout Plan creato con successo',
            'data' => new WorkoutPlanResource($workoutPlan),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkoutPlan $workout_plan): JsonResponse
    {
        $this->authorize('view', $workout_plan);

        $workout_plan->load('exercises');

        return response()->json([
            'data' => new WorkoutPlanResource($workout_plan),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateWorkoutPlanRequest $request, WorkoutPlan $workout_plan): JsonResponse
    {
        $validated = $request->validated();
        if (array_key_exists('client_id', $validated)) {
            $workout_plan->update(['client_id' => $validated['client_id']]);
        }
        if (array_key_exists('exercises', $validated)) {
            $syncData = [];
            foreach ($validated['exercises'] as $exercise) {
                $syncData[$exercise['id']] = [
                    'sets' => $exercise['sets'],
                    'reps' => $exercise['reps'],
                ];
            }
            $workout_plan->exercises()->sync($syncData);
        }
        $workout_plan->load(['exercises', 'client'])->loadCount('exercises');
        return response()->json([
            'message' => 'Workout Plan aggiornato con successo',
            'data' => new WorkoutPlanResource($workout_plan),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteWorkoutPlanRequest $request, WorkoutPlan $workout_plan): JsonResponse
    {
        $workout_plan->delete();

        return response()->json([
            'message' => 'Workout Plan eliminato con successo',
        ], 200);
    }
}
