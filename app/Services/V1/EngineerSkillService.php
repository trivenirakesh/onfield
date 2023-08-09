<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Models\EngineerSkill;
use App\Models\User;

class EngineerSkillService
{
    use CommonTrait;
    const module = 'Engineer skill';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $getSkillsData = EngineerSkill::query()
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->latest('id')->get();
        return $this->successResponseArr(self::module . __('messages.success.list'), $getSkillsData);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateSkills(Request $request, $userId)
    {
        $userskills = EngineerSkill::where('user_id', $userId)->latest('id')->get();
        $engineerSkills = $userskills->pluck('skill_id')->toArray();
        $requestSkills = $request->skills;

        // Skills to be deleted (present in current skills but not in request skills)
        $skillsToDelete = array_diff($engineerSkills, $requestSkills);

        // Skills to be added (present in request skills but not in current skills)
        $skillsToAdd = array_diff($requestSkills, $engineerSkills);

        $datetime = now()->format('Y-m-d H:i:s');
        $skills = [];
        foreach ($skillsToAdd as $val) {
            $skillArr = [
                'user_id' => $userId,
                'skill_id' => $val,
                'created_ip' =>  CommonHelper::getUserIp(),
                'created_at' => $datetime,
                'updated_at' => $datetime,
            ];
            $skills[] = $skillArr;
        }

        if (!empty($skills)) {
            EngineerSkill::insert($skills);
        }
        if (!empty($skillsToDelete)) {
            EngineerSkill::whereIn('user_id', $userId)->whereIn('skill_id', $skillsToDelete)->delete();
        }
        $userskills = EngineerSkill::where('user_id', $userId)->latest('id')->get();
        return $this->successResponseArr(self::module . __('messages.success.update'), $userskills);
    }
}
