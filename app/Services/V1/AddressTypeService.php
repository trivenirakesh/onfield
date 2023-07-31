<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\AddressType;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\AddressTypeResource;
use App\Helpers\CommonHelper;

class AddressTypeService
{
    use CommonTrait;
    const module = 'Address Type';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getSkillsData =  AddressTypeResource::collection(AddressType::latest('id')->get());
        return $this->successResponseArr(self::module . __('messages.success.list'), $getSkillsData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save addressType section
        $addressType = new AddressType();
        $addressType->name = $request->name;
        $addressType->status = $request->status;
        $addressType->created_by = auth()->user()->id;
        $addressType->created_ip = CommonHelper::getUserIp();
        $addressType->save();
        $getAddressTypeDetails = new AddressTypeResource($addressType);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getAddressTypeDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getAddressTypeData = AddressType::where('id', $id)->first();
        if ($getAddressTypeData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getAddressTypeData = new AddressTypeResource($getAddressTypeData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getAddressTypeData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $addressType = AddressType::where('id', $id)->first();
        if ($addressType == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $addressType->name = $request->name;
        $addressType->status = $request->status;
        $addressType->updated_by = auth()->user()->id;
        $addressType->updated_ip = CommonHelper::getUserIp();

        $addressType->update();
        $getAddressTypeDetails = new AddressTypeResource($addressType);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getAddressTypeDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $addressType =  AddressType::where('id', $id)->first();
        if ($addressType == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete addressType
        $addressType->deleted_by = auth()->user()->id;
        $addressType->deleted_ip = CommonHelper::getUserIp();
        $addressType->update();
        $deleteAddressType = $addressType->delete();
        if ($deleteAddressType) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
