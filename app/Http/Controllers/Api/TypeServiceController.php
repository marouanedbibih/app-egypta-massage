<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TypeService;
use App\Http\Requests\StoreTypeServiceRequest;
use App\Http\Requests\UpdateTypeServiceRequest;
use App\Http\Resources\TypeServiceResource;
use App\Models\Duration;

class TypeServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeServices = TypeService::select('id', 'label', 'description')
            ->orderBy('id', 'desc')
            ->get();
        return TypeServiceResource::collection($typeServices);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTypeServiceRequest $request)
    {
        if ($request->validated()) {
            $typeServiceData = $request->only(['label', 'description', 'service_id']);
            $durations = $request->input('durations');
            // $service_id =$request['service_id'];
            $typeService = TypeService::create($typeServiceData);
            foreach ($durations as $duration) {
                $typeService->duration()->create([
                    'duration' => $duration['duration'],
                    'price' => $duration['price']
                ]);
            }

            return response([
                'message' => 'Type massage create succufly with durationsand prices',
                'data' => new TypeServiceResource($typeService)
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(TypeService $typeService)
    {
        return new TypeServiceResource($typeService);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTypeServiceRequest $request, TypeService $typeService)
    {
        if ($request->validated()) {
            $typeServiceData = $request->only(['label', 'description', 'service_id']);
            $durations = $request->input('durations');

            $typeService->update($typeServiceData);

            // Delete existing durations and recreate with updated data
            $typeService->duration()->delete();
            foreach ($durations as $duration) {
                $typeService->duration()->create([
                    'duration' => $duration['duration'],
                    'price' => $duration['price']
                ]);
            }

            return response([
                'message' => 'Type massage updated successfully with durations and prices',
                'data' => new TypeServiceResource($typeService)
            ], 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TypeService $typeService)
    {
        // Delete associated durations first
        $typeService->duration()->delete();

        // Then delete the type service
        $typeService->delete();

        return response([
            'message' => 'Type massage and associated durations deleted successfully'
        ], 200);
    }

    public function getDurationsByTypeService(TypeService $typeService)
    {
        $durations = $typeService->duration()->get($columns = ['type_service_id', 'duration', 'price']);

        return response([
            'message' => 'Durations fetched successfully for the given TypeService',
            'data' => $durations
        ], 200);
    }

    public function deleteDuration(TypeService $typeService, $duration)
    {
        $deletedRows = Duration::where('type_service_id', $typeService->id)
            ->where('duration', $duration)
            ->delete();

        if ($deletedRows > 0) {
            return response()->json(['message' => 'Duration deleted from TypeService successfully'], 200);
        } else {
            return response()->json(['message' => 'Duration not found for TypeService'], 404);
        }
    }
}
