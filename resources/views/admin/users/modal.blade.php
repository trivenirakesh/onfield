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
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Name</b></label><br>
                                            <p id="info_name"></p>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="col-form-label"><b>Status</b></label><br>
                                            <p id="info_status_text"></p>
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal_title">Large Modal</h4>
                <button type="button" class="close" style="font-size: 20px;" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <form class="form-horizontal" id="module_form" action="{{route('admin.users.store')}}" name="module_form" novalidate="novalidate">
                <div class="modal-body">
                    <div class="card-body">
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="role_id"  value="2">
                        <div class="row">
                            <div class="form-group d-flex align-items:center">
                                <select class="form-control form-control-sm" name="status" id="status">
                                    <option value="1" selected>Active</option>
                                    <option value="0">InActive</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>First name <span class="red">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter first name" id="first_name" name="first_name" value="">
                                    <label id="name-error" class="text-danger" for="name"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Last name <span class="red">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter last name" id="last_name" name="last_name" value="">
                                    <label id="name-error" class="text-danger" for="name"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Email <span class="red">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter email" id="email" name="email" value="">
                                    <label id="name-error" class="text-danger" for="name"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Mobile <span class="red">*</span></label>
                                    <input type="text" class="form-control" placeholder="Please enter mobile" id="mobile" name="mobile" value="">
                                    <label id="name-error" class="text-danger" for="name"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Entity type <span class="red">*</span></label>
                                    <select name="entity_type" id="" class="form-control">
                                        <option value="0">Back office</option>
                                        <option value="1">Engineer</option>
                                        <option value="2">Client</option>
                                        <option value="3">Vendor</option>
                                    </select>
                                    <label id="name-error" class="text-danger" for="name"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Password <span class="red">*</span></label>
                                    <input type="password" class="form-control" placeholder="Please enter password" id="password" name="password" value="">
                                    <label id="name-error" class="text-danger" for="name"></label>
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