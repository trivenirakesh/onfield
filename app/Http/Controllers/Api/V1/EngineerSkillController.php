<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\EngineerSkillRequest;
use App\Http\Resources\V1\EngineerSkillResource;
use App\Services\V1\EngineerSkillService;
use App\Traits\CommonTrait;

class EngineerSkillController extends Controller
{
    use CommonTrait;
    private $engineerSkillService;

    public function __construct(EngineerSkillService $engineerSkillService)
    {
        $this->engineerSkillService = $engineerSkillService;
    }

    public function index()
    {
        $getSkills =  $this->engineerSkillService->index() ?? [];
        if (!$getSkills['status']) {
            return response()->json($getSkills, 401);
        }
        $getSkills['data'] = EngineerSkillResource::collection($getSkills['data']);
        return response()->json($getSkills, 200);
    }


    public function update(EngineerSkillRequest $request)
    {
        $updateSkill = $this->engineerSkillService->updateSkills($request, auth()->id());
        if (!$updateSkill['status']) {
            return response()->json($updateSkill, 401);
        }
        $updateSkill['data'] = EngineerSkillResource::collection($updateSkill['data']);
        return response()->json($updateSkill, 200);
    }
}
