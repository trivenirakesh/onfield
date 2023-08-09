<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ScheduleExceptionRequest;
use App\Http\Resources\V1\ScheduleExceptionResource;
use App\Services\V1\ScheduleExceptionService;
use App\Traits\CommonTrait;

class EngineerScheduleExceptionController extends Controller
{
    use CommonTrait;
    private $scheduleExceptionService;

    public function __construct(ScheduleExceptionService $scheduleExceptionService)
    {
        $this->scheduleExceptionService = $scheduleExceptionService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result =  $this->scheduleExceptionService->index();
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        $result['data'] = ScheduleExceptionResource::collection($result['data']);
        return response()->json($result, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleExceptionRequest $request)
    {
        $result  = $this->scheduleExceptionService->store($request, auth()->id());
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        $result['data'] = new ScheduleExceptionResource($result['data']);
        return response()->json($result, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getDetails = $this->scheduleExceptionService->show($id);
        if (!$getDetails['status']) {
            return response()->json($getDetails, 404);
        }
        $getDetails['data'] = new ScheduleExceptionResource($getDetails['data']);
        return response()->json($getDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ScheduleExceptionRequest $request, $id)
    {
        $result = $this->scheduleExceptionService->update($request, $id, auth()->id());
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        $result['data'] = new ScheduleExceptionResource($result['data']);
        return response()->json($result, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->scheduleExceptionService->destroy($id);
        if (!$result['status']) {
            return response()->json($result, 404);
        }
        return response()->json($result, 200);
    }
}
