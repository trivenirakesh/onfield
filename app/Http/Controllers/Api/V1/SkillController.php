<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\SkillCreateUpdateRequest;
use App\Http\Resources\V1\SkillResource;
use App\Services\V1\SkillService;
use App\Traits\CommonTrait;

class SkillController extends Controller
{
    use CommonTrait;
    private $skillService;

    public function __construct(SkillService $skillService)
    {
        $this->skillService = $skillService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getSkills =  $this->skillService->index() ?? [];
        $getSkills['data'] = SkillResource::collection($getSkills['data']);
        if (!$getSkills['status']) {
            return response()->json($getSkills, 401);
        }
        return response()->json($getSkills, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SkillCreateUpdateRequest $request)
    {
        $saveSkill  = $this->skillService->store($request);
        $saveSkill['data'] = new SkillResource($saveSkill['data']);
        if (!$saveSkill['status']) {
            return response()->json($saveSkill, 401);
        }
        return response()->json($saveSkill, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getSkillDetails = $this->skillService->show($id);
        $getSkillDetails['data'] = new SkillResource($getSkillDetails['data']);
        if (!$getSkillDetails['status']) {
            return response()->json($getSkillDetails, 401);
        }
        return response()->json($getSkillDetails, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SkillCreateUpdateRequest $request, $id)
    {
        $updateSkill = $this->skillService->update($request, $id);
        $updateSkill['data'] = new SkillResource($updateSkill['data']);
        if (!$updateSkill['status']) {
            return response()->json($updateSkill, 401);
        }
        return response()->json($updateSkill, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleteSkill = $this->skillService->destroy($id);
        if (!$deleteSkill['status']) {
            return response()->json($deleteSkill, 401);
        }
        return response()->json($deleteSkill, 200);
    }
}
