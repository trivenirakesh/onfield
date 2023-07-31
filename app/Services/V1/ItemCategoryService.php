<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\ItemCategory;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\ItemCategoryResource;
use App\Helpers\CommonHelper;

class ItemCategoryService
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
        $getItemCategoryData =  ItemCategoryResource::collection(ItemCategory::latest('id')->get());
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
        // Save item category section
        $itemCategory = new ItemCategory();
        $itemCategory->name = $request->name;
        $itemCategory->status = $request->status;
        $itemCategory->created_by = auth()->user()->id;
        $itemCategory->created_ip = CommonHelper::getUserIp();
        $itemCategory->save();
        $getItemCategoryDetails = new ItemCategoryResource($itemCategory);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getItemCategoryDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getItemCategoryData = ItemCategory::where('id', $id)->first();
        if ($getItemCategoryData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getItemCategoryData = new ItemCategoryResource($getItemCategoryData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getItemCategoryData);
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

        $itemCategory = ItemCategory::where('id', $id)->first();
        if ($itemCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $itemCategory->name = $request->name;
        $itemCategory->status = $request->status;
        $itemCategory->updated_by = auth()->user()->id;
        $itemCategory->updated_ip = CommonHelper::getUserIp();
        $itemCategory->update();

        $getItemCategoryDetails = new ItemCategoryResource($itemCategory);
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
        $itemCategory =  ItemCategory::where('id', $id)->first();
        if ($itemCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete itemCategory
        $itemCategory->deleted_by = auth()->user()->id;
        $itemCategory->deleted_ip = CommonHelper::getUserIp();
        $itemCategory->update();
        $deleteItemCategory = $itemCategory->delete();
        if ($deleteItemCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
