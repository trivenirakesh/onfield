<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductCategoryCreateUpdateRequest;
use App\Models\ProductCategory;
use App\Services\V1\ProductCategoryService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductCategoryController extends Controller
{
    use CommonTrait;
    protected $productCategoryService;

    public function __construct(ProductCategoryService $productCategoryService)
    {
        $this->productCategoryService = $productCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.productcategory.index');
            $data = ProductCategory::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->editColumn('name', function($row){
                    return CommonHelper::shortString($row->name,30);
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'name', 'status_text'])
                ->make(true);
        }
        $title =  'Product Category';
        return view('admin.productcategory.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCategoryCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateProductCategory = $this->productCategoryService->update($request, $request->id);
        } else { //add data
            $createUpdateProductCategory  = $this->productCategoryService->store($request);
        }
        if (!$createUpdateProductCategory['status']) {
            return $this->jsonResponse($createUpdateProductCategory, 401);
        }
        return $this->jsonResponse($createUpdateProductCategory, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getProductCategoryDetails = $this->productCategoryService->show($id);
        if (!$getProductCategoryDetails['status']) {
            return $this->jsonResponse($getProductCategoryDetails, 401);
        }
        return $this->jsonResponse($getProductCategoryDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteProductCategory = $this->productCategoryService->destroy($id);
        if (!$deleteProductCategory['status']) {
            return $this->jsonResponse($deleteProductCategory, 401);
        }
        return $this->jsonResponse($deleteProductCategory, 200);
    }
}
