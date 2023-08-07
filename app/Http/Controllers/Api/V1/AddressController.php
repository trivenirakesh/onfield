<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AddressCreateUpdateRequest;
use App\Http\Resources\V1\AddressResource;
use App\Http\Resources\V1\AddressTypeResource;
use App\Services\V1\AddressService;
use App\Services\V1\AddressTypeService;
use App\Traits\CommonTrait;

class AddressController extends Controller
{
    use CommonTrait;
    private $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result =  $this->addressService->index();
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        $result['data'] = AddressResource::collection($result['data']);
        return response()->json($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressCreateUpdateRequest $request)
    {
        $result  = $this->addressService->store($request);
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        $result['data'] = new AddressResource($result['data']);
        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getDetails = $this->addressService->show($id);
        if (!$getDetails['status']) {
            return response()->json($getDetails, 404);
        }
        $getDetails['data'] = new AddressResource($getDetails['data']);
        return response()->json($getDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AddressCreateUpdateRequest $request, $id)
    {
        $result = $this->addressService->update($request, $id);
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        $result['data'] = new AddressResource($result['data']);
        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->addressService->destroy($id);
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        return response()->json($result, 200);
    }

    /**
     * Get address types
     */
    public function addressType()
    {
        $addressType = new AddressTypeService();
        $response = $addressType->index();
        if (!$response['status']) {
            return $this->jsonResponse($response, 404);
        }
        $response['data'] =  AddressTypeResource::collection($response['data']);
        return $this->jsonResponse($response, 200);
    }
}
