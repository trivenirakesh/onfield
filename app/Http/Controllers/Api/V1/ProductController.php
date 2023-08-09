<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductCreateUpdateRequest;
use App\Services\V1\ProductService;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getProducts =  $this->productService->index() ?? [];
        if (!$getProducts['status']) {
            return response()->json($getProducts, 401);
        }
        return response()->json($getProducts, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductCreateUpdateRequest $request)
    {
        $saveProduct  = $this->productService->store($request);
        if (!$saveProduct['status']) {
            return response()->json($saveProduct, 401);
        }
        return response()->json($saveProduct, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getProductDetails = $this->productService->show($id);
        if (!$getProductDetails['status']) {
            return response()->json($getProductDetails, 401);
        }
        return response()->json($getProductDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCreateUpdateRequest $request, $id)
    {
        $updateProduct = $this->productService->update($request, $id);
        if (!$updateProduct['status']) {
            return response()->json($updateProduct, 401);
        }
        return response()->json($updateProduct, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteProduct = $this->productService->destroy($id);
        if (!$deleteProduct['status']) {
            return response()->json($deleteProduct, 401);
        }
        return response()->json($deleteProduct, 200);
    }
}
