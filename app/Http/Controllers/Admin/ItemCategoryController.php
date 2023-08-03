<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemCategoryCreateUpdateRequest;
use App\Models\ItemCategory;
use App\Services\V1\ItemCategoryService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemCategoryController extends Controller
{
    use CommonTrait;
    protected $itemCategoryService;

    public function __construct(ItemCategoryService $itemCategoryService)
    {
        $this->itemCategoryService = $itemCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.itemcategory.index');
            $data = ItemCategory::latest()->get();
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
        $title =  'Item Category';
        return view('admin.itemcategory.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemCategoryCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateItemCategory = $this->itemCategoryService->update($request, $request->id);
        } else { //add data
            $createUpdateItemCategory  = $this->itemCategoryService->store($request);
        }
        if (!$createUpdateItemCategory['status']) {
            return $this->jsonResponse($createUpdateItemCategory, 401);
        }
        return $this->jsonResponse($createUpdateItemCategory, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getItemCategoryDetails = $this->itemCategoryService->show($id);
        if (!$getItemCategoryDetails['status']) {
            return $this->jsonResponse($getItemCategoryDetails, 401);
        }
        return $this->jsonResponse($getItemCategoryDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteItemCategory = $this->itemCategoryService->destroy($id);
        if (!$deleteItemCategory['status']) {
            return $this->jsonResponse($deleteItemCategory, 401);
        }
        return $this->jsonResponse($deleteItemCategory, 200);
    }
}
