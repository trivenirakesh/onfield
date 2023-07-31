<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AddressTypeCreateUpdateRequest;
use App\Services\V1\AddressTypeService;

class AddressTypeController extends Controller
{
    private $addressTypeService;

    public function __construct(AddressTypeService $addressTypeService)
    {
        $this->addressTypeService = $addressTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getAddressType =  $this->addressTypeService->index() ?? [];
        if (!$getAddressType['status']) {
            return response()->json($getAddressType, 401);
        }
        return response()->json($getAddressType, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressTypeCreateUpdateRequest $request)
    {
        $saveAddressType  = $this->addressTypeService->store($request);
        if (!$saveAddressType['status']) {
            return response()->json($saveAddressType, 401);
        }
        return response()->json($saveAddressType, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getAddressTypeDetails = $this->addressTypeService->show($id);
        if (!$getAddressTypeDetails['status']) {
            return response()->json($getAddressTypeDetails, 401);
        }
        return response()->json($getAddressTypeDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddressTypeCreateUpdateRequest $request, $id)
    {
        $updateAddressType = $this->addressTypeService->update($request, $id);
        if (!$updateAddressType['status']) {
            return response()->json($updateAddressType, 401);
        }
        return response()->json($updateAddressType, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteAddressType = $this->addressTypeService->destroy($id);
        if (!$deleteAddressType['status']) {
            return response()->json($deleteAddressType, 401);
        }
        return response()->json($deleteAddressType, 200);
    }
}
