<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Models\ProductCategory;

class ProductCategoryService
{
    use CommonTrait;
    const module = 'Product Category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getProductCategoryData = ProductCategory::latest('id')->get();
        return $this->successResponseArr(self::module . __('messages.success.list'), $getProductCategoryData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save product category section
        $input = $request->validated();
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();
        $productCategory = ProductCategory::create($input);
        
        return $this->successResponseArr(self::module . __('messages.success.create'), $productCategory);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getProductCategoryData = ProductCategory::where('id', $id)->first();
        if ($getProductCategoryData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        return $this->successResponseArr(self::module . __('messages.success.details'), $getProductCategoryData);
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

        $productCategory = ProductCategory::where('id', $id)->first();
        if ($productCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $input = $request->validated();
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();

        $productCategory->update($input);
        return $this->successResponseArr(self::module . __('messages.success.update'), $productCategory);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $productCategory =  ProductCategory::where('id', $id)->first();
        if ($productCategory == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete productCategory
        $productCategory->deleted_by = auth()->user()->id;
        $productCategory->deleted_ip = CommonHelper::getUserIp();
        $productCategory->update();
        $deleteproductCategory = $productCategory->delete();
        if ($deleteproductCategory) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
