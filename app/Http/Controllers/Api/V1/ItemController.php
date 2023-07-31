<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemCreateUpdateRequest;
use App\Services\V1\ItemService;

class ItemController extends Controller
{
    private $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getItems =  $this->itemService->index() ?? [];
        if (!$getItems['status']) {
            return response()->json($getItems, 401);
        }
        return response()->json($getItems, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemCreateUpdateRequest $request)
    {
        $saveItem  = $this->itemService->store($request);
        if (!$saveItem['status']) {
            return response()->json($saveItem, 401);
        }
        return response()->json($saveItem, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getItemDetails = $this->itemService->show($id);
        if (!$getItemDetails['status']) {
            return response()->json($getItemDetails, 401);
        }
        return response()->json($getItemDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemCreateUpdateRequest $request, $id)
    {
        $updateItem = $this->itemService->update($request, $id);
        if (!$updateItem['status']) {
            return response()->json($updateItem, 401);
        }
        return response()->json($updateItem, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteItem = $this->itemService->destroy($id);
        if (!$deleteItem['status']) {
            return response()->json($deleteItem, 401);
        }
        return response()->json($deleteItem, 200);
    }
}
