<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\UnitOfMeasurement;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\UnitOfMeasurementResource;
use App\Helpers\CommonHelper;

class UnitOfMeasurementService
{
    use CommonTrait;
    const module = 'Item Category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getUnitOfMeasurementData =  UnitOfMeasurementResource::collection(UnitOfMeasurement::latest('id')->get());
        return $this->successResponseArr(self::module . __('messages.success.list'), $getUnitOfMeasurementData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save unit of measurement section
        $unitOfMeasurement = new UnitOfMeasurement();
        $unitOfMeasurement->name = $request->name;
        $unitOfMeasurement->description = $request->description;
        $unitOfMeasurement->factor = $request->factor;
        $unitOfMeasurement->status = $request->status;
        $unitOfMeasurement->created_by = auth()->user()->id;
        $unitOfMeasurement->created_ip = CommonHelper::getUserIp();
        $unitOfMeasurement->save();
        $getUnitOfMeasurementDetails = new UnitOfMeasurementResource($unitOfMeasurement);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getUnitOfMeasurementDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getUnitOfMeasurementData = UnitOfMeasurement::where('id', $id)->first();
        if ($getUnitOfMeasurementData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getUnitOfMeasurementData = new UnitOfMeasurementResource($getUnitOfMeasurementData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getUnitOfMeasurementData);
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

        $unitOfMeasurement = UnitOfMeasurement::where('id', $id)->first();
        if ($unitOfMeasurement == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $unitOfMeasurement->name = $request->name;
        $unitOfMeasurement->description = $request->description;
        $unitOfMeasurement->factor = $request->factor;
        $unitOfMeasurement->status = $request->status;
        $unitOfMeasurement->updated_by = auth()->user()->id;
        $unitOfMeasurement->updated_ip = CommonHelper::getUserIp();
        $unitOfMeasurement->update();

        $getUnitOfMeasurementDetails = new UnitOfMeasurementResource($unitOfMeasurement);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getUnitOfMeasurementDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unitOfMeasurement =  UnitOfMeasurement::where('id', $id)->first();
        if ($unitOfMeasurement == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete unitOfMeasurement
        $unitOfMeasurement->deleted_by = auth()->user()->id;
        $unitOfMeasurement->deleted_ip = CommonHelper::getUserIp();
        $unitOfMeasurement->update();
        $deleteUnitOfMeasurement = $unitOfMeasurement->delete();
        if ($deleteUnitOfMeasurement) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
