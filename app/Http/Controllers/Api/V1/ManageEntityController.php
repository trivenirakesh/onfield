<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\EntityCreateUpdateRequest;
use App\Services\V1\ManageEntityService;

class ManageEntityController extends Controller
{
    private $entityService;

    public function __construct(ManageEntityService $entityService)
    {
        $this->entityService = $entityService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getEntity =  $this->entityService->index() ?? [];
        if (!$getEntity['status']) {
            return response()->json($getEntity, 401);
        }
        return response()->json($getEntity, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntityCreateUpdateRequest $request)
    {
        $saveEntity  = $this->entityService->store($request);
        if (!$saveEntity['status']) {
            return response()->json($saveEntity, 401);
        }
        return response()->json($saveEntity, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getEntityDetails = $this->entityService->show($id);
        if (!$getEntityDetails['status']) {
            return response()->json($getEntityDetails, 401);
        }
        return response()->json($getEntityDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EntityCreateUpdateRequest $request, $id)
    {
        $updateEntity = $this->entityService->update($request, $id);
        if (!$updateEntity['status']) {
            return response()->json($updateEntity, 401);
        }
        return response()->json($updateEntity, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteEntity = $this->entityService->destroy($id);
        if (!$deleteEntity['status']) {
            return response()->json($deleteEntity, 401);
        }
        return response()->json($deleteEntity, 200);
    }
}
