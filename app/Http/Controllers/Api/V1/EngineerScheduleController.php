<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ScheduleRequest;
use App\Http\Resources\V1\SchedulesResource;
use App\Services\V1\ScheduleService;
use App\Traits\CommonTrait;

class EngineerScheduleController extends Controller
{
    use CommonTrait;
    private $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    public function index()
    {
        $schedule =  $this->scheduleService->engineerSchedule() ?? [];
        if (!$schedule['status']) {
            return response()->json($schedule, 401);
        }
        $schedule['data'] = SchedulesResource::collection($schedule['data']);
        return response()->json($schedule, 200);
    }


    public function update(ScheduleRequest $request)
    {
        $updateSchedule = $this->scheduleService->engineerScheduleUpdate($request, auth()->id());
        if (!$updateSchedule['status']) {
            return response()->json($updateSchedule, 401);
        }
        $updateSchedule['data'] = SchedulesResource::collection($updateSchedule['data']);
        return response()->json($updateSchedule, 200);
    }
}
