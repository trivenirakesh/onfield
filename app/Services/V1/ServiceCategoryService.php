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
        if (auth()->user()->user_type == User::USERADMIN) {
            $getCategoryData = ServiceCategory::latest('id')->get();
        } else {
            $getCategoryData = ServiceCategory::where('status', $activeStatus)->latest('id')->get();
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
        $input = $request->only('name', 'description', 'status');
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();
        $serviceCategory = ServiceCategory::create($input);

        // upload file 
        $uploadPath = ServiceCategory::FOLDERNAME;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,$uploadPath , 0);
            if (!empty($data)) {
                $uploadsArr = [
                    'file' => $data['filename'],
                    'thumb_file' => $data['filename'],
                    'media_type' => ServiceCategory::MEDIA_TYPES[0],
                    'file_type' => $data['filetype'],
                    'upload_path' => CommonHelper::getUploadPath($uploadPath),
                    'created_ip' => CommonHelper::getUserIp(),
                    'created_by' => auth()->user()->id,
                    'reference_id' => $serviceCategory->id,
                    'reference_type' => ServiceCategory::class,
                ];
                Upload::create($uploadsArr);
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
        $input = $request->only('name', 'description', 'status');
        $serviceCategory = ServiceCategory::with(['upload' => function ($query) {
            $query->select('id', 'reference_id', 'reference_type', 'file', 'media_type', 'file_type', 'upload_path');
        }])->where('id', $id)->first();
        if ($serviceCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();
        $serviceCategory->update($input);
        
        // Update file
        $uploadPath = ServiceCategory::FOLDERNAME;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath, 0);
            if (!empty($data)) {
                // delete file if exists
                if ($serviceCategory?->upload?->id != null) {
                    // Unlink old image from storage 
                    $oldImage = $serviceCategory?->upload->getAttributes()['file'] ?? null;
                    $path = $serviceCategory->upload->upload_path ?? null;
                    if ($oldImage != null && $path != null) {
                        CommonHelper::removeUploadedImages($oldImage, $path);
                    }
                    $serviceCategory->upload->delete();
                }

                $uploadsArr = [
                    'file' => $data['filename'],
                    'thumb_file' => $data['filename'],
                    'media_type' => ServiceCategory::MEDIA_TYPES[0],
                    'file_type' => $data['filetype'],
                    'upload_path' => CommonHelper::getUploadPath($uploadPath),
                    'created_ip' => CommonHelper::getUserIp(),
                    'created_by' => auth()->user()->id,
                    'reference_id' => $id,
                    'reference_type' => ServiceCategory::class,
                ];
                Upload::create($uploadsArr);
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
        $deleteUploads = Upload::where('reference_type', ServiceCategory::class)->where('reference_id', $id)->first();
        $deleteUploads->deleted_by = auth()->user()->id;
        $deleteUploads->deleted_ip = CommonHelper::getUserIp();
        $deleteUploads->update();
        $deleteUploads = $deleteUploads->delete();
        if ($deleteRole) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }


    // /**
    //  * Display a listing of the resource.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function recentServices()
    // {
    //     $activeStatus = CommonHelper::getConfigValue('status.active');
    //     $getCategoryData = ServiceCategory::when(auth()->user()->user_type != User::USERADMIN, function ($query) use ($activeStatus) {
    //         $query->where('status', $activeStatus);
    //     })
    //         ->latest('id')->get();

    //     $getServiceCategoryData =  ServiceCategoryResource::collection($getCategoryData);
    //     return $this->successResponseArr(self::module . __('messages.success.list'), $getServiceCategoryData);
    // }
}
