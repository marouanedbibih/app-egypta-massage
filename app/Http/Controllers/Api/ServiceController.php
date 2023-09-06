<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Category;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::select('id', 'label', 'description')
            ->orderBy('id', 'desc')
            ->get();
        return  ServiceResource::collection($services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        if ($request->validated()) {
            $serviceData = $request->only(['label', 'description']);
            $typeServices = $request->input('type_services');
            $categorieId = Category::findOrFail($request->only(['categorie']));
            // creation de Service
            $service = Service::create($serviceData);
            // integration categorie for service
            $service->categorie()->attach($categorieId);
            // create type service for service
            foreach ($typeServices as $typeService) {
                $service->typeService()->create([
                    'label' => $typeService['label'],
                    'description' => $typeService['description']
                ]);
            }
            return response([
                'message' => 'Service created succufuly',
                'Service' => new ServiceResource($service),
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        return new ServiceResource($service);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        if ($request->validated()) {
            $serviceData = $request->only(['label', 'description']);
            $service->update($serviceData);

            // Update the pivot table (service_categories) if 'categorie' is provided
            $categorieId = $request->input('categorie');
            if ($categorieId !== null) {
                // Sync the relationship to update the pivot table
                $service->categorie()->sync([$categorieId]);
            }

            return response()->json([
                'message' => 'Service updated successfully',
                'service' => new ServiceResource($service),
            ], 200);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return response(['message' => 'service delete succufuly'], 200);
    }


    public function getTypeServices(Service $service)
    {
        $typeServices = $service->typeService()
            ->get($columns = ['id', 'label', 'description', 'service_id']);

        return response()->json([
            'message' => 'Type services retrieved successfully',
            'type_services' => $typeServices,
        ], 200);
    }
}
