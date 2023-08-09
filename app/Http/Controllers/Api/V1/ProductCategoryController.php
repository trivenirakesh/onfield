<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductCategoryCreateUpdateRequest;
use App\Http\Resources\V1\ProductCategoryResource;
use App\Services\V1\ProductCategoryService;
use Database\Seeders\ProductCategorySeeder;

class ProductCategoryController extends Controller
{
    private $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getProductCategory =  $this->productCategoryService->index() ?? [];
        $getProductCategory['data'] = ProductCategoryResource::collection($getProductCategory);
        if (!$getProductCategory['status']) {
            return response()->json($getProductCategory, 401);
        }
        return response()->json($getProductCategory, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCategoryCreateUpdateRequest $request)
    {
        $saveProductCategory  = $this->productCategoryService->store($request);
        $saveProductCategory['data'] = new ProductCategoryResource($saveProductCategory);
        if (!$saveProductCategory['status']) {
            return response()->json($saveProductCategory, 401);
        }
        return response()->json($saveProductCategory, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getProductCategoryDetails = $this->productCategoryService->show($id);
        $getProductCategoryDetails['data'] = new ProductCategorySeeder($getProductCategoryDetails);
        if (!$getProductCategoryDetails['status']) {
            return response()->json($getProductCategoryDetails, 401);
        }
        return response()->json($getProductCategoryDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryCreateUpdateRequest $request, $id)
    {
        $updateProductCategory = $this->productCategoryService->update($request, $id);
        $updateProductCategory = new ProductCategoryResource($updateProductCategory);
        if (!$updateProductCategory['status']) {
            return response()->json($updateProductCategory, 401);
        }
        return response()->json($updateProductCategory, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteProductCategory = $this->productCategoryService->destroy($id);
        if (!$deleteProductCategory['status']) {
            return response()->json($deleteProductCategory, 401);
        }
        return response()->json($deleteProductCategory, 200);
    }
}
