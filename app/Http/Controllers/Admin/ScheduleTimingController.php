<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\CommonTrait;
use App\Http\Requests\V1\Api\ScheduleRequest;
use App\Services\V1\ScheduleService;
use App\Models\Schedule;

class ScheduleTimingController extends Controller
{
    use CommonTrait;
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $title = 'Schedule Timing';
        $weeks = Schedule::DAYS;
        $getScheduleTimingDetails = $this->scheduleService->adminSchedule()['data'];
        return view('admin.schedule.index', compact('title','weeks','getScheduleTimingDetails'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScheduleRequest $request)  {
        $updateSchedule = $this->scheduleService->update($request);
        if (!$updateSchedule['status']) {
            return $this->jsonResponse($updateSchedule, 401);
        }
        return $this->jsonResponse($updateSchedule, 200);
    }
}
