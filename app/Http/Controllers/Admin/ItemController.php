<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ItemCreateUpdateRequest;
use App\Models\User;
use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\UnitOfMeasurement;
use App\Services\V1\ItemService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ItemController extends Controller
{
    use CommonTrait;
    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.item.index');
            $data = Item::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('image', function ($row) {
                    $imagePath = CommonHelper::getUploadUrl(Item::class,$row->id,Item::FOLDERNAME);
                    $image = '<img src="' . $imagePath . '" class="img-fluid img-radius" width="40px" height="40px">';
                    return $image;
                })
                ->editColumn('description', function($row){
                    return CommonHelper::shortString($row->description,30);
                })
                ->editColumn('uom', function($row){
                    return !empty($row->unitOfMeasurement['name']) ? $row->unitOfMeasurement['name'] : '-';
                })
                ->editColumn('item_category', function($row){
                    return !empty($row->itemCategory['name']) ? $row->itemCategory['name']  : '-';
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'image','name','uom', 'item_category','price','entity_type','status_text'])
                ->make(true);
        }
        $title =  'Item';
        $getItemCategoryData = CommonHelper::getTableWiseData(ItemCategory::class);
        $getUomData = CommonHelper::getTableWiseData(UnitOfMeasurement::class);
        $getVendorsData = CommonHelper::getTableWiseData(User::class,array('entity_type'=>User::ENTITYVENDOR));
        return view('admin.item.index', compact('title','getItemCategoryData','getUomData','getVendorsData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ItemCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateItem = $this->itemService->update($request, $request->id);
        } else { //add data
            $createUpdateItem  = $this->itemService->store($request);
        }
        if (!$createUpdateItem['status']) {
            return $this->jsonResponse($createUpdateItem, 401);
        }
        return $this->jsonResponse($createUpdateItem, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getItemDetails = $this->itemService->show($id);
        if (!$getItemDetails['status']) {
            return $this->jsonResponse($getItemDetails, 401);
        }
        return $this->jsonResponse($getItemDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteItem = $this->itemService->destroy($id);
        if (!$deleteItem['status']) {
            return $this->jsonResponse($deleteItem, 401);
        }
        return $this->jsonResponse($deleteItem, 200);
    }
}
