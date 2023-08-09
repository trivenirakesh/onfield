<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ServiceCategoryCreateUpdateRequest;
use App\Models\ServiceCategory;
use App\Services\V1\ServiceCategoryService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ServiceCategoryController extends Controller
{
    use CommonTrait;
    protected $serviceCategoryService;

    public function __construct(ServiceCategoryService $serviceCategoryService)
    {
        $this->serviceCategoryService = $serviceCategoryService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.servicecategory.index');
            $data = ServiceCategory::latest()->get();
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
                ->editColumn('name', function($row){
                    return CommonHelper::shortString($row->name,30);
                })
                ->editColumn('description', function($row){
                    return CommonHelper::shortString($row->description,30);
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete','image', 'name','description', 'status_text'])
                ->make(true);
        }
        $title =  'Service Category';
        return view('admin.servicecategory.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ServiceCategoryCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateServiceCategory = $this->serviceCategoryService->update($request, $request->id);
        } else { //add data
            $createUpdateServiceCategory  = $this->serviceCategoryService->store($request);
        }
        if (!$createUpdateServiceCategory['status']) {
            return $this->jsonResponse($createUpdateServiceCategory, 401);
        }
        return $this->jsonResponse($createUpdateServiceCategory, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getServiceCategoryDetails = $this->serviceCategoryService->show($id);
        if (!$getServiceCategoryDetails['status']) {
            return $this->jsonResponse($getServiceCategoryDetails, 401);
        }
        return $this->jsonResponse($getServiceCategoryDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteServiceCategory = $this->serviceCategoryService->destroy($id);
        if (!$deleteServiceCategory['status']) {
            return $this->jsonResponse($deleteServiceCategory, 401);
        }
        return $this->jsonResponse($deleteServiceCategory, 200);
    }
}
