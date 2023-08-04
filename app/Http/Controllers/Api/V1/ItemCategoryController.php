<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemCategoryCreateUpdateRequest;
use App\Http\Resources\V1\ItemCategoryResource;
use App\Services\V1\ItemCategoryService;
use Database\Seeders\ItemCategorySeeder;

class ItemCategoryController extends Controller
{
    private $itemCategoryService;

    public function __construct(ItemCategoryService $itemCategoryService)
    {
        $this->itemCategoryService = $itemCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getItemCategory =  $this->itemCategoryService->index() ?? [];
        $getItemCategory['data'] = ItemCategoryResource::collection($getItemCategory);
        if (!$getItemCategory['status']) {
            return response()->json($getItemCategory, 401);
        }
        return response()->json($getItemCategory, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemCategoryCreateUpdateRequest $request)
    {
        $saveItemCategory  = $this->itemCategoryService->store($request);
        $saveItemCategory['data'] = new ItemCategoryResource($saveItemCategory);
        if (!$saveItemCategory['status']) {
            return response()->json($saveItemCategory, 401);
        }
        return response()->json($saveItemCategory, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getItemCategoryDetails = $this->itemCategoryService->show($id);
        $getItemCategoryDetails['data'] = new ItemCategorySeeder($getItemCategoryDetails);
        if (!$getItemCategoryDetails['status']) {
            return response()->json($getItemCategoryDetails, 401);
        }
        return response()->json($getItemCategoryDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemCategoryCreateUpdateRequest $request, $id)
    {
        $updateItemCategory = $this->itemCategoryService->update($request, $id);
        $updateItemCategory = new ItemCategoryResource($updateItemCategory);
        if (!$updateItemCategory['status']) {
            return response()->json($updateItemCategory, 401);
        }
        return response()->json($updateItemCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteItemCategory = $this->itemCategoryService->destroy($id);
        if (!$deleteItemCategory['status']) {
            return response()->json($deleteItemCategory, 401);
        }
        return response()->json($deleteItemCategory, 200);
    }
}
