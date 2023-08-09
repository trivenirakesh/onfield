<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;



class ScheduleService
{
    use CommonTrait;
    const module = 'Schedule';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scheduleData = [];
        return $this->successResponseArr(self::module . __('messages.success.list'), $scheduleData);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function engineerSchedule()
    {
        $scheduleData = [];
        return $this->successResponseArr(self::module . __('messages.success.list'), $scheduleData);
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        $scheduleData = [];
        return $this->successResponseArr(self::module . __('messages.success.update'), $scheduleData);
    }
}
