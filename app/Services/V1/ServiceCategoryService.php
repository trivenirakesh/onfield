<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\ServiceCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\ServiceCategoryResource;
use App\Helpers\CommonHelper;
use App\Models\User;
use App\Models\Upload;

class ServiceCategoryService
{
    use CommonTrait;
    const module = 'Service Category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeStatus = CommonHelper::getConfigValue('status.active');
        if(auth()->user()->entity_type == User::ENTITYADMIN){
            $getCategoryData = ServiceCategory::latest('id')->get();
        }else{
            $getCategoryData = ServiceCategory::where('status',$activeStatus)->latest('id')->get();
        }
        $getServiceCategoryData =  ServiceCategoryResource::collection($getCategoryData);
        return $this->successResponseArr(self::module . __('messages.success.list'), $getServiceCategoryData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save serviceCategory section
        $serviceCategory = new ServiceCategory();
        $serviceCategory->name = $request->name;
        $serviceCategory->description = $request->description;
        $serviceCategory->status = $request->status;
        $serviceCategory->created_by = auth()->user()->id;
        $serviceCategory->created_ip = CommonHelper::getUserIp();
        $serviceCategory->save();

        // upload file 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,ServiceCategory::FOLDERNAME,0);
            if (!empty($data)) {
                $saveUploads = new Upload();
                $saveUploads['file'] = $data['filename'];
                $saveUploads['transaction_type'] = 'SERVICE_CATEGORY';
                $saveUploads['media_type'] = ServiceCategory::MEDIA_TYPES[0];
                $saveUploads['image_type'] = $data['filetype'];
                $saveUploads['created_by'] = auth()->user()->id;
                $saveUploads['created_ip'] = CommonHelper::getUserIp();
                $saveUploads['reference_id'] = $serviceCategory->id;
                $saveUploads['reference_type'] = ServiceCategory::class;
                $saveUploads->save();
            }
        }

        $getServiceCategoryDetails = new ServiceCategoryResource($serviceCategory);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getServiceCategoryDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getServiceCategoryData = ServiceCategory::where('id', $id)->first();
        if ($getServiceCategoryData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getServiceCategoryData = new ServiceCategoryResource($getServiceCategoryData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getServiceCategoryData);
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

        $serviceCategory = ServiceCategory::where('id', $id)->first();
        if ($serviceCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $serviceCategory->name = $request->name;
        $serviceCategory->description = $request->description;
        $serviceCategory->status = $request->status;
        $serviceCategory->updated_by = auth()->user()->id;
        $serviceCategory->updated_ip = CommonHelper::getUserIp();
        $serviceCategory->update();

        // Update file
        if ($request->hasFile('image')) {
            $updateUploads = Upload::where('reference_type',ServiceCategory::class)->where('reference_id',$id)->first();
            // Unlink old image from storage 
            $oldImage = $updateUploads->file ?? null;
            if ($oldImage != null){
                CommonHelper::removeUploadedImages($oldImage,ServiceCategory::FOLDERNAME);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,ServiceCategory::FOLDERNAME,0);
            if (!empty($data)) {
                $updateUploads->file = $data['filename'];
                $updateUploads->image_type = $data['filetype'];
                $updateUploads->updated_by = auth()->user()->id;
                $updateUploads->updated_ip = CommonHelper::getUserIp();
                $updateUploads->update();
            }
        }
        $getServiceCategoryDetails = new ServiceCategoryResource($serviceCategory);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getServiceCategoryDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $serviceCategory =  ServiceCategory::where('id', $id)->first();
        if ($serviceCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete role
        $serviceCategory->deleted_by = auth()->user()->id;
        $serviceCategory->deleted_ip = CommonHelper::getUserIp();
        $serviceCategory->update();
        $deleteRole = $serviceCategory->delete();
        $deleteUploads = Upload::where('reference_type',ServiceCategory::class)->where('reference_id',$id)->first();
        $deleteUploads->deleted_by = auth()->user()->id;
        $deleteUploads->deleted_ip = CommonHelper::getUserIp();
        $deleteUploads->update();
        $deleteUploads = $deleteUploads->delete();
        if ($deleteRole) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
