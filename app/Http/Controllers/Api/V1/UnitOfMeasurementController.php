<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UnitOfMeasurementCreateUpdateRequest;
use App\Http\Resources\V1\UnitOfMeasurementResource;
use App\Services\V1\UnitOfMeasurementService;

class UnitOfMeasurementController extends Controller
{
    private $unitOfMeasurementService;

    public function __construct(UnitOfMeasurementService $unitOfMeasurementService)
    {
        $this->unitOfMeasurementService = $unitOfMeasurementService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getUnitOfMeasurement =  $this->unitOfMeasurementService->index() ?? [];
        $getUnitOfMeasurement['data'] = UnitOfMeasurementResource::collection($getUnitOfMeasurement);
        if (!$getUnitOfMeasurement['status']) {
            return response()->json($getUnitOfMeasurement, 401);
        }
        return response()->json($getUnitOfMeasurement, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UnitOfMeasurementCreateUpdateRequest $request)
    {
        $saveUnitOfMeasurement  = $this->unitOfMeasurementService->store($request);
        $saveUnitOfMeasurement['data'] = new UnitOfMeasurementResource($saveUnitOfMeasurement);
        if (!$saveUnitOfMeasurement['status']) {
            return response()->json($saveUnitOfMeasurement, 401);
        }
        return response()->json($saveUnitOfMeasurement, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUnitOfMeasurementDetails = $this->unitOfMeasurementService->show($id);
        $getUnitOfMeasurementDetails['data'] = new UnitOfMeasurementResource($getUnitOfMeasurementDetails);
        if (!$getUnitOfMeasurementDetails['status']) {
            return response()->json($getUnitOfMeasurementDetails, 401);
        }
        return response()->json($getUnitOfMeasurementDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UnitOfMeasurementCreateUpdateRequest $request, $id)
    {
        $updateUnitOfMeasurement = $this->unitOfMeasurementService->update($request, $id);
        $updateUnitOfMeasurement['data'] = new UnitOfMeasurementResource($updateUnitOfMeasurement);
        if (!$updateUnitOfMeasurement['status']) {
            return response()->json($updateUnitOfMeasurement, 401);
        }
        return response()->json($updateUnitOfMeasurement, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteUnitOfMeasurement = $this->unitOfMeasurementService->destroy($id);
        if (!$deleteUnitOfMeasurement['status']) {
            return response()->json($deleteUnitOfMeasurement, 401);
        }
        return response()->json($deleteUnitOfMeasurement, 200);
    }
}
