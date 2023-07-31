<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\ItemResource;
use App\Helpers\CommonHelper;
use App\Models\Upload;

class ItemService
{
    use CommonTrait;
    const module = 'Item';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getItemData =  ItemResource::collection(Item::latest('id')->get());
        return $this->successResponseArr(self::module . __('messages.success.list'), $getItemData);
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
        $saveItem = new Item;
        $saveItem->name = $request->name;
        $saveItem->description = $request->description;
        $saveItem->uom_id = $request->uom_id;
        $saveItem->item_category_id = $request->item_category_id;
        $saveItem->price = $request->price;
        $saveItem->status = $request->status;
        if($request->has('is_vendor') && $request->has('vendor_id')){
            $saveItem->is_vendor = $request->is_vendor;
            $saveItem->vendor_id = $request->vendor_id;
        }
        $saveItem->created_by = auth()->user()->id;
        $saveItem->created_ip = CommonHelper::getUserIp();
        $saveItem->save();

        // upload file 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,Item::FOLDERNAME,0);
            if (!empty($data)) {
                $saveUploads = new Upload();
                $saveUploads['file'] = $data['filename'];
                $saveUploads['transaction_type'] = 'ITEM';
                $saveUploads['media_type'] = 'BASE';
                $saveUploads['image_type'] = $data['filetype'];
                $saveUploads['created_by'] = auth()->user()->id;
                $saveUploads['created_ip'] = CommonHelper::getUserIp();
                $saveUploads['reference_id'] = $saveItem->id;
                $saveUploads['reference_type'] = Item::class;
                $saveUploads->save();
            }
        }
        $getItemDetails = new ItemResource($saveItem);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getItemDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getItemData = Item::where('id', $id)->first();
        if ($getItemData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getItemData = new ItemResource($getItemData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getItemData);
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

        $itemCategory = Item::where('id', $id)->first();
        if ($itemCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $itemCategory->name = $request->name;
        $itemCategory->status = $request->status;
        $itemCategory->updated_by = auth()->user()->id;
        $itemCategory->updated_ip = CommonHelper::getUserIp();
        $itemCategory->update();

        // Update file
        if ($request->hasFile('image')) {
            $updateUploads = Upload::where('reference_type',Item::class)->where('reference_id',$id)->first();
            // Unlink old image from storage 
            $oldImage = $updateUploads->file ?? null;
            if ($oldImage != null){
                CommonHelper::removeUploadedImages($oldImage,Item::FOLDERNAME);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,Item::FOLDERNAME,0);
            if (!empty($data)) {
                $updateUploads->file = $data['filename'];
                $updateUploads->image_type = $data['filetype'];
                $updateUploads->updated_by = auth()->user()->id;
                $updateUploads->updated_ip = CommonHelper::getUserIp();
                $updateUploads->update();
            }
        }
        $getItemDetails = new ItemResource($itemCategory);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getItemDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $itemCategory =  Item::where('id', $id)->first();
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
