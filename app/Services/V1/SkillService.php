<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Models\Skill;
use App\Traits\CommonTrait;
use App\Http\Resources\V1\SkillResource;
use App\Helpers\CommonHelper;
use App\Models\Entitymst;

class SkillService
{
    use CommonTrait;
    const module = 'Skill';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeStatus = CommonHelper::getConfigValue('status.active');
        if(auth()->user()->entity_type == Entitymst::ENTITYADMIN){
            $getSkillsData = Skill::latest('id')->get();
        }else{
            $getSkillsData = Skill::where('status',$activeStatus)->latest('id')->get();
        }
        $getSkillsData =  SkillResource::collection($getSkillsData);
        return $this->successResponseArr(self::module . __('messages.success.list'), $getSkillsData);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Save skill section
        $input = $request->validated();
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();
        $skill = Skill::create($input);
        $getSkillDetails = new SkillResource($skill);
        return $this->successResponseArr(self::module . __('messages.success.create'), $getSkillDetails);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $getSkillData = Skill::where('id', $id)->first();
        if ($getSkillData == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $getSkillData = new SkillResource($getSkillData);
        return $this->successResponseArr(self::module . __('messages.success.details'), $getSkillData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $skill = Skill::where('id', $id)->first();
        if ($skill == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $input = $request->validated();
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();

        $skill->update($input);
        $getSkillDetails = new SkillResource($skill);
        return $this->successResponseArr(self::module . __('messages.success.update'), $getSkillDetails);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $skill =  Skill::where('id', $id)->first();
        if ($skill == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }

        // Delete skill
        $skill->deleted_by = auth()->user()->id;
        $skill->deleted_ip = CommonHelper::getUserIp();
        $skill->update();
        $deleteSkill = $skill->delete();
        if ($deleteSkill) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
