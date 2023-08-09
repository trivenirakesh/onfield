<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\EngineerSkillRequest;
use App\Http\Resources\V1\EngineerSkillResource;
use App\Services\V1\ScheduleService;
use App\Traits\CommonTrait;
use GuzzleHttp\Psr7\Request;

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
        $schedule['data'] = [];
        return response()->json($schedule, 200);
    }


    public function update(Request $request)
    {
        // $updateSkill = $this->scheduleService->updateSkills($request, auth()->id());
        // if (!$updateSkill['status']) {
        //     return response()->json($updateSkill, 401);
        // }
        // $updateSkill['data'] = EngineerSkillResource::collection($updateSkill['data']);
        // return response()->json($updateSkill, 200);
    }
}
