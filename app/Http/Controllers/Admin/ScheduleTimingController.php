<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;
use App\Models\Schedule;

class ScheduleTimingController extends Controller
{
    public function index(){
        $title = 'Schedule Timing';
        $weeks = Schedule::DAYS;
        $getScheduleTimingDetails = Schedule::select('id','work_day','start_time','end_time','status')->whereNull('user_id')->get();
        return view('admin.schedule.index', compact('title','weeks','getScheduleTimingDetails'));
    }

    public function store(Request $request) : Array {
        dd($request->all());
    }
}
