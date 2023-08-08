<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\ProductResource;
use App\Helpers\CommonHelper;
use App\Models\Upload;

class ProductService
{
    use CommonTrait;
    const module = 'Product';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getProductData =  ProductResource::collection(Product::latest('id')->get());
        return $this->successResponseArr(self::module . __('messages.success.list'), $getProductData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save product  section
        $saveProduct = new Product;
        $saveProduct->name = $request->name;
        $saveProduct->description = $request->description;
        $saveProduct->unit_of_measurement_id = $request->unit_of_measurement_id;
        $saveProduct->product_category_id = $request->product_category_id;
        $saveProduct->price = $request->price;
        $saveProduct->status = $request->status;
        if($request->has('is_vendor') && $request->has('user_id')){
            $saveProduct->is_vendor = $request->is_vendor;
            $saveProduct->user_id = $request->user_id;
        }
        $saveProduct->created_by = auth()->user()->id;
        $saveProduct->created_ip = CommonHelper::getUserIp();
        $saveProduct->save();

        // upload file
        $uploadPath = Product::FOLDERNAME; 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,$uploadPath,0);
            if (!empty($data)) {
                $saveUploads = new Upload();
                $saveUploads['file'] = $data['filename'];
                $saveUploads['media_type'] = Product::MEDIA_TYPES[0];
                $saveUploads['image_type'] = $data['filetype'];
                $saveUploads['upload_path'] = CommonHelper::getUploadPath($uploadPath);
                $saveUploads['created_by'] = auth()->user()->id;
                $saveUploads['created_ip'] = CommonHelper::getUserIp();
                $saveUploads['reference_id'] = $saveProduct->id;
                $saveUploads['reference_type'] = Product::class;
                $saveUploads->save();
            }
        }
        $getProductDetails = new ProductResource($saveProduct);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getProductDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getProductData = Product::where('id', $id)->first();
        if ($getProductData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getProductData = new ProductResource($getProductData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getProductData);
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

        $updateProduct = Product::where('id', $id)->first();
        if ($updateProduct == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $updateProduct->name = $request->name;
        $updateProduct->status = $request->status;
        $updateProduct->name = $request->name;
        $updateProduct->description = $request->description;
        $updateProduct->uom_id = $request->uom_id;
        $updateProduct->product_category_id = $request->product_category_id;
        $updateProduct->price = $request->price;
        if($request->has('is_vendor') && $request->has('user_id')){
            $updateProduct->is_vendor = $request->is_vendor;
            $updateProduct->user_id = $request->user_id;
        }
        $updateProduct->updated_by = auth()->user()->id;
        $updateProduct->updated_ip = CommonHelper::getUserIp();
        $updateProduct->update();

        // Update file
        if ($request->hasFile('image')) {
            $updateUploads = Upload::where('reference_type',Product::class)->where('reference_id',$id)->first();
            // Unlink old image from storage 
            $oldImage = $updateUploads->file ?? null;
            if ($oldImage != null){
                CommonHelper::removeUploadedImages($oldImage,Product::FOLDERNAME);
            }
            // Unlink old image from storage 

            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,Product::FOLDERNAME,0);
            if (!empty($data)) {
                $updateUploads->file = $data['filename'];
                $updateUploads->image_type = $data['filetype'];
                $updateUploads->updated_by = auth()->user()->id;
                $updateUploads->updated_ip = CommonHelper::getUserIp();
                $updateUploads->update();
            }
        }
        $getProductDetails = new ProductResource($updateProduct);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getProductDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product =  Product::where('id', $id)->first();
        if ($product == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete product
        $product->deleted_by = auth()->user()->id;
        $product->deleted_ip = CommonHelper::getUserIp();
        $product->update();
        $deleteProduct = $product->delete();
        if ($deleteProduct) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
