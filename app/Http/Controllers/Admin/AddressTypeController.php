<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\AddressTypeCreateUpdateRequest;
use App\Models\AddressType;
use App\Services\V1\AddressTypeService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AddressTypeController extends Controller
{
    use CommonTrait;
    protected $addressTypeService;

    public function __construct(AddressTypeService $addressTypeService)
    {
        $this->addressTypeService = $addressTypeService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.addresstype.index');
            $data = AddressType::where('id','!=',1)->latest()->get();
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
        $title =  'Address Type';
        return view('admin.addresstype.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AddressTypeCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateAddressType = $this->addressTypeService->update($request, $request->id);
        } else { //add data
            $createUpdateAddressType  = $this->addressTypeService->store($request);
        }
        if (!$createUpdateAddressType['status']) {
            return $this->jsonResponse($createUpdateAddressType, 401);
        }
        return $this->jsonResponse($createUpdateAddressType, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getAddressTypeDetails = $this->addressTypeService->show($id);
        if (!$getAddressTypeDetails['status']) {
            return $this->jsonResponse($getAddressTypeDetails, 401);
        }
        return $this->jsonResponse($getAddressTypeDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteAddressType = $this->addressTypeService->destroy($id);
        if (!$deleteAddressType['status']) {
            return $this->jsonResponse($deleteAddressType, 401);
        }
        return $this->jsonResponse($deleteAddressType, 200);
    }
}
