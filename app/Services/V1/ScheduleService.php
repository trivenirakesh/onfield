<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Models\Schedule;

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
        $schedules = Schedule::where('user_id', auth()->id())
            ->orderBy('work_day')
            ->get();
        if (count($schedules) == 0) {
            $schedules = Schedule::where('user_id', null)
                ->orderBy('work_day')
                ->get();
        }
        return $this->successResponseArr(self::module . __('messages.success.list'), $schedules);
    }

    public function engineerScheduleUpdate(Request $request, $userId)
    {
        $daysOfWeek = Schedule::DAYS;
        $inputDays = array_column($request->schedule, 'work_day');

        // Validate that each day is included exactly once
        $isValid = (empty(array_diff($daysOfWeek, $inputDays)) && empty(array_diff($inputDays, $daysOfWeek)));
        if (!$isValid) {
            return $this->errorResponseArr(__('messages.validation.invalid_schedule_request'));
        }

        foreach ($request->schedule as $entry) {
            Schedule::updateOrCreate(
                [
                    'user_id' => $userId,
                    'work_day' => $entry['work_day'],
                ],
                [
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                    'status' => $entry['status'],
                ]
            );
        }

        $schedules = $this->engineerSchedule()['data'];
        return $this->successResponseArr(self::module . __('messages.success.update'), $schedules);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminSchedule()
    {
        $schedules = Schedule::where('user_id', null)
            ->orderBy('work_day')
            ->get();
        return $this->successResponseArr(self::module . __('messages.success.list'), $schedules);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $scheduleData = [];
        $daysOfWeek = Schedule::DAYS;
        $inputDays = array_column($request->schedule, 'work_day');
        
        // Validate that each day is included exactly once
        $isValid = (empty(array_diff($daysOfWeek, $inputDays)) && empty(array_diff($inputDays, $daysOfWeek)));
        if (!$isValid) {
            return $this->errorResponseArr(__('messages.validation.invalid_schedule_request'));
        }
        foreach ($request->schedule as $entry) {
            Schedule::updateOrCreate(
                [
                    'id' => $entry['id'],
                    'work_day' => $entry['work_day'],
                ],
                [
                    'start_time' => $entry['start_time'],
                    'end_time' => $entry['end_time'],
                    'status' => $entry['status'],
                ]
            );
        }
        $getBackOfficeSchedule = $this->adminSchedule()['data'];
        return $this->successResponseArr(self::module . __('messages.success.update'), $getBackOfficeSchedule);
    }
}
