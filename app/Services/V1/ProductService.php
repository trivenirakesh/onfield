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
        $input = $request->only('name', 'description', 'unit_of_measurement_id','product_category_id','price','status');
        if($request->has('is_vendor') && $request->has('user_id')){
            $input['is_vendor'] = $request->is_vendor;
            $input['user_id'] = $request->user_id;
        }
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();
        $saveProduct = Product::create($input);

        // upload file
        $uploadPath = Product::FOLDERNAME; 
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image,$uploadPath,0);
            if (!empty($data)) {
                $uploadsArr = [
                    'file' => $data['filename'],
                    'thumb_file' => $data['filename'],
                    'media_type' => Product::MEDIA_TYPES[0],
                    'file_type' => $data['filetype'],
                    'upload_path' => CommonHelper::getUploadPath($uploadPath),
                    'created_ip' => CommonHelper::getUserIp(),
                    'created_by' => auth()->user()->id,
                    'reference_id' => $saveProduct->id,
                    'reference_type' => Product::class,
                ];
                Upload::create($uploadsArr);
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
        $input = $request->only('name', 'description', 'unit_of_measurement_id','product_category_id','price','status');
        $updateProduct = Product::with(['upload' => function ($query) {
            $query->select('id', 'reference_id', 'reference_type', 'file', 'media_type', 'file_type', 'upload_path');
        }])->where('id', $id)->first();
        if ($updateProduct == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        if($request->has('is_vendor') && $request->has('user_id')){
            $input['is_vendor'] = $request->is_vendor;
            $input['user_id'] = $request->user_id;
        }
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();
        $updateProduct->update($input);

        // Update file
        $uploadPath = Product::FOLDERNAME;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $data = CommonHelper::uploadImages($image, $uploadPath, 0);
            if (!empty($data)) {
                // delete file if exists
                if ($updateProduct?->upload?->id != null) {
                    // Unlink old image from storage 
                    $oldImage = $updateProduct?->upload->getAttributes()['file'] ?? null;
                    $path = $updateProduct->upload->upload_path ?? null;
                    if ($oldImage != null && $path != null) {
                        CommonHelper::removeUploadedImages($oldImage, $path);
                    }
                    $updateProduct->upload->delete();
                }

                $uploadsArr = [
                    'file' => $data['filename'],
                    'thumb_file' => $data['filename'],
                    'media_type' => Product::MEDIA_TYPES[0],
                    'file_type' => $data['filetype'],
                    'upload_path' => CommonHelper::getUploadPath($uploadPath),
                    'created_ip' => CommonHelper::getUserIp(),
                    'created_by' => auth()->user()->id,
                    'reference_id' => $id,
                    'reference_type' => Product::class,
                ];
                Upload::create($uploadsArr);
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
