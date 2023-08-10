<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Http\Resources\V1\ScheduleExceptionResource;
use App\Http\Resources\V1\SchedulesResource;
use App\Models\Schedule;
use App\Models\ScheduleException;

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

    public function bookingSchedule()
    {
        $schedules = Schedule::where('user_id', null)
            ->orderBy('work_day')
            ->get();
        $schedule_exception = ScheduleException::where('user_id', null)
            ->orderBy('start_date')
            ->get();

        $schedules = SchedulesResource::collection($schedules);
        $schedule_exception = ScheduleExceptionResource::collection($schedule_exception);
        $slot_gap = Schedule::SLOTGAP;
        $data = compact('schedules', 'schedule_exception', 'slot_gap');
        return $this->successResponseArr("Booking Schedule" . __('messages.success.list'), $data);
    }

    public function bookingScheduleByDate(Request $request)
    {
        //prepare time slot for date 
        // $schedules = Schedule::where('user_id', null)
        //     ->orderBy('work_day')
        //     ->get();
        // $schedule_exception = ScheduleException::where('user_id', null)
        //     ->orderBy('start_date')
        //     ->get();

        // $schedules = SchedulesResource::collection($schedules);
        // $schedule_exception = ScheduleExceptionResource::collection($schedule_exception);
        // $slot_gap = Schedule::SLOTGAP;
        // $data = compact('schedules', 'schedule_exception', 'slot_gap');
        $data = [];
        return $this->successResponseArr("Booking Schedule" . __('messages.success.list'), $data);
    }
}
