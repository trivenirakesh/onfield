<?php

namespace App\Services\V1;

use Illuminate\Http\Request;
use App\Traits\CommonTrait;
use App\Helpers\CommonHelper;
use App\Models\ScheduleException;
use App\Models\User;

class ScheduleExceptionService
{
    use CommonTrait;
    const module = 'Schedule exception';

    /**
     * Note: pass userId only for handle user related resources
     */

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activeStatus = CommonHelper::getConfigValue('status.active');
        $response = ScheduleException::query()
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) use ($activeStatus) {
                $query->where('user_id', auth()->id());
            })
            ->latest('id')->get();
        return $this->successResponseArr(self::module . __('messages.success.list'), $response);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $userId = null)
    {
        //validate date  conflict
        $newStartDate = $request->start_date;
        $newEndDate = $request->end_date;
        $isExists =  ScheduleException::when($userId != null, function ($query)  use ($userId) {
            $query->where('user_id', $userId);
        })->where(function ($query) use ($newStartDate, $newEndDate) {
            $query->whereBetween('start_date', [$newStartDate, $newEndDate])
                ->orWhereBetween('end_date', [$newStartDate, $newEndDate])
                ->orWhere(function ($q) use ($newStartDate, $newEndDate) {
                    $q->where('start_date', '<=', $newStartDate)
                        ->where('end_date', '>=', $newEndDate);
                });
        })->exists();
        if ($isExists) {
            return $this->errorResponseArr(__('messages.failed.schedule_exception_invalid'));
        }

        $input = $request->validated();
        $input['user_id'] = $userId;
        $input['created_by'] = auth()->user()->id;
        $input['created_ip'] = CommonHelper::getUserIp();
        $result = ScheduleException::create($input);
        return $this->successResponseArr(self::module . __('messages.success.create'), $result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = ScheduleException::query()
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->where('id', $id)->first();
        if ($result == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        return $this->successResponseArr(self::module . __('messages.success.details'), $result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $userId = null)
    {

        //validate date  conflict
        $newStartDate = $request->start_date;
        $newEndDate = $request->end_date;
        $isExists =  ScheduleException::when($userId != null, function ($query)  use ($userId) {
            $query->where('user_id', $userId);
        })->where('id', '!=', $id)->where(function ($query) use ($newStartDate, $newEndDate) {
            $query->whereBetween('start_date', [$newStartDate, $newEndDate])
                ->orWhereBetween('end_date', [$newStartDate, $newEndDate])
                ->orWhere(function ($q) use ($newStartDate, $newEndDate) {
                    $q->where('start_date', '<=', $newStartDate)
                        ->where('end_date', '>=', $newEndDate);
                });
        })->exists();
        if ($isExists) {
            return $this->errorResponseArr(__('messages.failed.schedule_exception_invalid'));
        }

        $result = ScheduleException::where('id', $id)
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->first();
        if ($result == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $input = $request->validated();
        $input['user_id'] = $userId;
        $input['updated_by'] = auth()->user()->id;
        $input['updated_ip'] = CommonHelper::getUserIp();
        $result->update($input);
        return $this->successResponseArr(self::module . __('messages.success.update'), $result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item =  ScheduleException::where('id', $id)
            ->when(auth()->user()->user_type != User::USERADMIN, function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->first();
        if ($item == null) {
            return $this->errorResponseArr(self::module . __('messages.validation.not_found'));
        }
        $item->deleted_by = auth()->user()->id;
        $item->deleted_ip = CommonHelper::getUserIp();
        $item->update();
        $result = $item->delete();
        if ($result) {
            return $this->successResponseArr(self::module . __('messages.success.delete'));
        }
    }
}
