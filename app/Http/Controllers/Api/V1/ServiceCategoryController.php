<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ServiceCategoryCreateUpdateRequest;
use App\Services\V1\ServiceCategoryService;

class ServiceCategoryController extends Controller
{
    private $serviceCategoryService;

    public function __construct(ServiceCategoryService $serviceCategoryService)
    {
        $this->serviceCategoryService = $serviceCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getServiceCategory =  $this->serviceCategoryService->index() ?? [];
        if (!$getServiceCategory['status']) {
            return response()->json($getServiceCategory, 401);
        }
        return response()->json($getServiceCategory, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceCategoryCreateUpdateRequest $request)
    {
        $saveServiceCategory  = $this->serviceCategoryService->store($request);
        if (!$saveServiceCategory['status']) {
            return response()->json($saveServiceCategory, 401);
        }
        return response()->json($saveServiceCategory, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getServiceCategoryDetails = $this->serviceCategoryService->show($id);
        if (!$getServiceCategoryDetails['status']) {
            return response()->json($getServiceCategoryDetails, 401);
        }
        return response()->json($getServiceCategoryDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceCategoryCreateUpdateRequest $request, $id)
    {
        $updateServiceCategory = $this->serviceCategoryService->update($request, $id);
        if (!$updateServiceCategory['status']) {
            return response()->json($updateServiceCategory, 401);
        }
        return response()->json($updateServiceCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteServiceCategory = $this->serviceCategoryService->destroy($id);
        if (!$deleteServiceCategory['status']) {
            return response()->json($deleteServiceCategory, 401);
        }
        return response()->json($deleteServiceCategory, 200);
    }
}
