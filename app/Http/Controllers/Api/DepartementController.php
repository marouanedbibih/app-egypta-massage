<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use App\Http\Requests\StoreDepartementRequest;
use App\Http\Requests\UpdateDepartementRequest;
use App\Http\Resources\DepartementResource;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depart = Departement::select('id', 'label', 'longitude', 'latitude')
            ->orderBy('id', 'asc')
            ->get();
        return response([
            'data' =>  DepartementResource::collection($depart)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDepartementRequest $request)
    {
        $data = $request->validated();
        $departement = Departement::create($data);
        return response([
            'message' => 'create departement succufuly',
            'data' => $departement
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Departement $departement)
    {
        return response([
            'data' => new DepartementResource($departement)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDepartementRequest $request, Departement $departement)
    {
        $data = $request->validated();
        $departement->update($data);
        return response([
            'message' => 'your departement update sucufuly',
            'data' => new DepartementResource($departement)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Departement $departement)
    {
        $departement->delete();
        return response(['message' => 'your departement detele succufuly',], 200);
    }

    public function addColabToDepart($departementId, $userId)
    {
        $departement = Departement::findOrFail($departementId);
        $user = User::findOrFail($userId);

        $departement->user()->attach($user);
        $colab = $this->getUsersByDepartement($departementId);
        return response([
            'message' => 'User added to department successfully',
            'colab' => $colab
        ], 200);
    }


    public function removeColabFromDepart($departementId, $userId)
    {
        $departement = Departement::findOrFail($departementId);
        $user = User::findOrFail($userId);

        $departement->user()->detach($user);

        return response(['message' => 'User removed from department successfully'],200);
    }


    // Create the api for this function
    public function getUsersByDepartement($departementId)
    {
        $departement = Departement::findOrFail($departementId);

        $users = $departement->user;

        return response(['data' => $users]);
    }
}
