<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ScheduleExceptionRequest;
use App\Models\ScheduleException;
use App\Services\V1\ScheduleExceptionService;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ScheduleExceptionController extends Controller
{
    use CommonTrait;
    protected $scheduleExceptionService;

    public function __construct(ScheduleExceptionService $scheduleExceptionService)
    {
        $this->scheduleExceptionService = $scheduleExceptionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $baseurl = route('admin.scheduleexception.index');
            $data = ScheduleException::whereNull('user_id')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action_edit', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, false);
                })
                ->addColumn('action_delete', function ($row) use ($baseurl) {
                    return $this->actionHtml($baseurl, $row->id, true);
                })
                ->addColumn('all_day', function ($row) use ($baseurl) {
                    return $row->all_day == 0 ? 'No' : 'Yes';
                })
                ->rawColumns(['action_edit', 'action_delete', 'start_date','end_date','all_day', 'start_time','end_time'])
                ->make(true);
        }
        $title =  'Schedule Exception';
        return view('admin.scheduleexception.index', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleExceptionRequest $request)
    {
        if (isset($request->id) && $request->id > 0) { //update data
            $createUpdateAddressType = $this->scheduleExceptionService->update($request, $request->id);
        } else { //add data
            $createUpdateAddressType  = $this->scheduleExceptionService->store($request);
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
        $getAddressTypeDetails = $this->scheduleExceptionService->show($id);
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
        $deleteAddressType = $this->scheduleExceptionService->destroy($id);
        if (!$deleteAddressType['status']) {
            return $this->jsonResponse($deleteAddressType, 401);
        }
        return $this->jsonResponse($deleteAddressType, 200);
    }
}
