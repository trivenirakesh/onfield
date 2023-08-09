<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\CommonHelper;

class ScheduleTimingController extends Controller
{
    public function index(){
        $title = 'Schedule Timing';
        $weeks = CommonHelper::getConfigValue('week_name');
        return view('admin.schedule.index', compact('title','weeks'));
    }
}
