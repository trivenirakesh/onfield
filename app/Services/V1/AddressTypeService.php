<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\AddressType;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\AddressTypeResource;
use App\Helpers\CommonHelper;
use App\Models\Entitymst;

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
        $activeStatus = CommonHelper::getConfigValue('status.active');
        if(auth()->user()->entity_type == Entitymst::ENTITYADMIN){
            $getAddressTypeData = AddressType::where('id','!=',1)->latest('id')->get();
        }else{
            $getAddressTypeData = AddressType::where('id','!=',1)->where('status',$activeStatus)->latest('id')->get();
        }
        $getSkillsData =  AddressTypeResource::collection($getAddressTypeData);
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
        $input = $request->validated();
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();

        $addressType = AddressType::create($input);
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
        $input = $request->validated();
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();

        $addressType->update($input);
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
