<!-- View details -->
<div class="modal fade" id="view_module_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="show_modal_title">View</h4>
                <button type="button" class="close" style="font-size: 20px;" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body">
                <div class="card-body align-items-center justify-content-center loader" id="modal_loader1" style="display: none;"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>
                <div class="card-body" id="modal_body_part">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>From Date</b></label><br>
                                            <p id="info_start_date"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>End Date</b></label><br>
                                            <p id="info_end_date"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>Start Time</b></label><br>
                                            <p id="info_start_time"></p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-form-label"><b>End Time</b></label><br>
                                            <p id="info_end_time"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Created At</b></label><br>
                                            <p id="info_created_at"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" aria-label="Close" class="btn btn-secondary float-right">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- add update modal -->
<div class="modal fade" id="modal-add-update" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Large Modal</h4>
                <button type="button" class="close" style="font-size: 20px;" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form class="form-horizontal" id="module_form" action="{{route('admin.scheduleexception.store')}}" name="module_form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Start Date <span class="red">*</span></label>
                                    <div class="input-group date" id="startdate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="start_date" data-target="#startdate">
                                        <div class="input-group-append" data-target="#startdate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <label id="start_date-error" class="text-danger" for="start_date"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>End Date <span class="red">*</span></label>
                                    <div class="input-group date" id="enddate" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="end_date" data-target="#enddate">
                                        <div class="input-group-append" data-target="#enddate" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                        <label id="end_date-error" class="text-danger" for="end_date"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>Full Day Off </label>
                                    <input type="checkbox" class="form-control fullDayOffCheckbox" id="all_day" value="">
                                    <label id="name-error" class="text-danger" for="name"></label>
                                </div>
                            </div>
                            <div class="col-sm-5 hideSection">
                                <div class="form-group">
                                    <label>Start Time </label>
                                    <div class="input-group date" id="timepicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="start_time" data-target="#timepicker">
                                        <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                        <label id="start_time-error" class="text-danger" for="start_time"></label> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-5 hideSection">
                                <div class="form-group">
                                    <label>End Time </label>
                                    <div class="input-group date" id="endpicker" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" name="end_time" data-target="#endpicker">
                                        <div class="input-group-append" data-target="#endpicker" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="far fa-clock"></i></div>
                                        </div>
                                        <label id="end_time-error" class="text-danger" for="end_time"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span style="display: none" id="module_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                </div>
            </form>
        </div>
    </div>
</div>