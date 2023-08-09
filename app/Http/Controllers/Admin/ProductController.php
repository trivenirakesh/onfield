<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProductCreateUpdateRequest;
use App\Models\User;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasurement;
use App\Services\V1\ProductService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    use CommonTrait;
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.product.index');
            $data = Product::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('image', function ($row) {
                    $imagePath = !empty($row->upload->file) ? $row->upload->file : '';
                    $image = '<img src="' . $imagePath . '" class="img-fluid img-radius" width="40px" height="40px">';
                    return $image;
                })
                ->editColumn('description', function($row){
                    return CommonHelper::shortString($row->description,30);
                })
                ->editColumn('uom', function($row){
                    return !empty($row->unitOfMeasurement['name']) ? $row->unitOfMeasurement['name'] : '-';
                })
                ->editColumn('product_category', function($row){
                    return !empty($row->productCategory['name']) ? $row->productCategory['name']  : '-';
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'image','name','uom', 'product_category','price','status_text'])
                ->make(true);
        }
        $title =  'Product';
        $getProductCategoryData = CommonHelper::getTableWiseData(ProductCategory::class);
        $getUomData = CommonHelper::getTableWiseData(UnitOfMeasurement::class);
        $getVendorsData = CommonHelper::getTableWiseData(User::class,array('user_type'=>User::USERVENDOR));
        return view('admin.product.index', compact('title','getProductCategoryData','getUomData','getVendorsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateProduct = $this->productService->update($request, $request->id);
        } else { //add data
            $createUpdateProduct  = $this->productService->store($request);
        }
        if (!$createUpdateProduct['status']) {
            return $this->jsonResponse($createUpdateProduct, 401);
        }
        return $this->jsonResponse($createUpdateProduct, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getProductDetails = $this->productService->show($id);
        if (!$getProductDetails['status']) {
            return $this->jsonResponse($getProductDetails, 401);
        }
        return $this->jsonResponse($getProductDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteProduct = $this->productService->destroy($id);
        if (!$deleteProduct['status']) {
            return $this->jsonResponse($deleteProduct, 401);
        }
        return $this->jsonResponse($deleteProduct, 200);
    }
}
