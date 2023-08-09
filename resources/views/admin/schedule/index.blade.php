@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body py-4 px-2">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for=""></label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">Day</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Start Time</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">End Time</label>
                                    </div>
                                </div>
                            </div>
                            <form method="POST" id="updateScheduleTimingFrm">
                                @csrf
                            @for($i=0;$i < count($weeks);$i++)
                            <div class="row dayWiseRow">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input type="hidden" name="id" class="schedule_id" value="@if(isset($getScheduleTimingDetails) && !empty($getScheduleTimingDetails[$i]['id'])){{$getScheduleTimingDetails[$i]['id']}}@endif">
                                        <input type="hidden" name="day" class="day_name" value="@if(isset($getScheduleTimingDetails) && !empty($getScheduleTimingDetails[$i]['work_day'])){{$getScheduleTimingDetails[$i]['work_day']}}@endif">
                                        <input type="checkbox" class="day_status" name="status" @if(isset($getScheduleTimingDetails) && !empty($getScheduleTimingDetails[$i]['status'])) checked @endif value="@if(isset($getScheduleTimingDetails) && !empty($getScheduleTimingDetails[$i]['status'])){{$getScheduleTimingDetails[$i]['status']}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="">{{ strtoupper($weeks[$i])}}</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control start_time timepicker" name="start_time" value="@if(isset($getScheduleTimingDetails) && !empty($getScheduleTimingDetails[$i]['start_time'])){{$getScheduleTimingDetails[$i]['start_time']}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control end_time timepicker" name="end_time" value="@if(isset($getScheduleTimingDetails) && !empty($getScheduleTimingDetails[$i]['end_time'])){{$getScheduleTimingDetails[$i]['end_time']}}@endif">
                                    </div>
                                </div>
                            </div>
                            @endfor
                        </div>
                        <div class="modal-footer ">
                            <button type="submit" class="float-right btn btn-primary" id="module_form_btn">Update<span style="display: none" id="users_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="page_module" value="{{$title}}">
    <input type="hidden" id="module_update_url" value="{{ route('admin.schedule_timing_update') }}">
</div>
@endsection
@push('script')
<script src="{{asset('public/asset/js/schedule.js')}}"></script>
@endpush