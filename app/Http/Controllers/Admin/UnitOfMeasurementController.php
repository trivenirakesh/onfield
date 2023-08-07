<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\UnitOfMeasurementCreateUpdateRequest;
use App\Models\UnitOfMeasurement;
use App\Services\V1\UnitOfMeasurementService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UnitOfMeasurementController extends Controller
{
    use CommonTrait;
    protected $unitOfMeasurementService;

    public function __construct(UnitOfMeasurementService $unitOfMeasurementService)
    {
        $this->unitOfMeasurementService = $unitOfMeasurementService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.unitofmeasurement.index');
            $data = UnitOfMeasurement::latest()->get();
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
                ->editColumn('description', function($row){
                    return CommonHelper::shortString($row->description,30);
                })
                ->addColumn('status_text', function ($row) {
                    return $this->statusHtml($row);
                })
                ->rawColumns(['action_edit', 'action_delete', 'name','description','factor', 'status_text'])
                ->make(true);
        }
        $title =  'Unit of Measurement';
        return view('admin.unitofmeasurement.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitOfMeasurementCreateUpdateRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateUom = $this->unitOfMeasurementService->update($request, $request->id);
        } else { //add data
            $createUpdateUom  = $this->unitOfMeasurementService->store($request);
        }
        if (!$createUpdateUom['status']) {
            return $this->jsonResponse($createUpdateUom, 401);
        }
        return $this->jsonResponse($createUpdateUom, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $getUnitOfMeasurementDetails = $this->unitOfMeasurementService->show($id);
        if (!$getUnitOfMeasurementDetails['status']) {
            return $this->jsonResponse($getUnitOfMeasurementDetails, 401);
        }
        return $this->jsonResponse($getUnitOfMeasurementDetails, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteUnitOfMeasurement = $this->unitOfMeasurementService->destroy($id);
        if (!$deleteUnitOfMeasurement['status']) {
            return $this->jsonResponse($deleteUnitOfMeasurement, 401);
        }
        return $this->jsonResponse($deleteUnitOfMeasurement, 200);
    }
}
