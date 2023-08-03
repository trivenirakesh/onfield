<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Entitymst;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\EntityResource;
use App\Http\Resources\V1\EntityDetailResource;
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Hash;

class ManageEntityService
{
    use CommonTrait;
    const module = 'User';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getItemCategoryData =  EntityResource::collection(Entitymst::latest('id')->get());
        return $this->successResponseArr(self::module . __('messages.success.list'), $getItemCategoryData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save entity section
        $entity = new Entitymst;
        $entity->first_name = $request->first_name;
        $entity->last_name = $request->last_name;
        $entity->email = str_replace(' ', '',$request->email);
        $entity->mobile = $request->mobile;
        $entity->entity_type = $request->entity_type;
        $entity->status = $request->status;
        $entity->password = Hash::make($request->password);
        $entity->role_id = $request->role_id;
        $entity->created_by = auth()->user()->id;
        $entity->created_ip = CommonHelper::getUserIp();
        $entity->save();
        return $this->successResponseArr(self::module . __('messages.success.create'), $entity);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getEntityData = Entitymst::where('id', $id)->first();
        if ($getEntityData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        return $this->successResponseArr(self::module . __('messages.success.details'), $getEntityData);
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

        $entity = Entitymst::where('id', $id)->first();
        if ($entity == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        // update entity 
        $entity->first_name = $request->first_name;
        $entity->last_name = $request->last_name;
        $entity->email = str_replace(' ', '',$request->email);
        $entity->mobile = $request->mobile;
        $entity->role_id = $request->role_id;
        $entity->updated_by = auth()->user()->id;
        $entity->updated_ip = CommonHelper::getUserIp();
        $entity->update();

        $getItemCategoryDetails = new EntityDetailResource($entity);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getItemCategoryDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $entity =  Entitymst::where('id', $id)->first();
        if ($entity == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete entity
        $entity->deleted_by = auth()->user()->id;
        $entity->deleted_ip = CommonHelper::getUserIp();
        $entity->update();
        $deleteEntity = $entity->delete();
        if ($deleteEntity) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}