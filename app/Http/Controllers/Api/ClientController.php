<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeleteClientRequest;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientCollection;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    use AuthorizesRequests;
    
    /**
     * GET /api/clients
     * Display a listing of the resource.
     */
    public function index(Request $request): ClientCollection
    {
        $perPage = min($request->input('per_page', 15), 100);
        $clients = $request->user()->clients()->paginate($perPage);

        return new ClientCollection($clients);
    }

    /**
     * POST /api/clients
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        // Validate the request
        $validated = $request->validated();

        // Create the client
        $client = $request->user()->clients()->create($validated);

        $client->load('personalTrainer');

        return response()->json([
            'message' => 'Cliente creato con successo',
            'data' => new ClientResource($client),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): JsonResponse
    {
        $this->authorize('view', $client);

        $client->load('personalTrainer');

        return response()->json([
            'data' => new ClientResource($client),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, Client $client): JsonResponse
    {
        $client->update($request->validated());
        $client->load('personalTrainer');

        return response()->json([
            'message' => 'Cliente aggiornato con successo',
            'data' => new ClientResource($client),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteClientRequest $request, Client $client): JsonResponse
    {
        $client->delete();

        return response()->json([
            'message' => 'Cliente eliminato con successo',
        ], 200);
    }
}
