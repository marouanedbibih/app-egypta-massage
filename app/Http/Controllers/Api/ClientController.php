<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = User::where('role', 0)
            ->select('id', 'name', 'email', 'phone', 'sex', 'profile_image') // Add the columns you want to select here
            ->orderBy('id', 'desc')
            ->paginate(7);
        return ClientResource::collection($clients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        $data = $request->validated();
        // Additional logic specific to the Client model can be added here
        $client = User::create($data);
        return response([
            'message' => 'Client added successfully',
            'client' => new ClientResource($client),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(User $client)
    {
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClientRequest $request, User $client)
    {
        $data = $request->validated();
        // Additional logic specific to the Client model can be added here
        $client->update($data);
        return response([
            'message' => 'Client updated successfully',
            'client' => new ClientResource($client),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $client)
    {
        $client->delete();
        return response([
            "message" => "Client deleted successfully"
        ], 204);
    }

    // You can add more methods specific to the Client model as needed
}
