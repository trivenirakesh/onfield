@extends('admin.layouts.admin')
@push('styles')
<link rel="stylesheet" href="{{asset('public/plugins/select2/css/select2.min.css')}}">
@endpush
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
            <div class="text-right d-inline-block">
                <a class=" btn btn-sm float-right  ml-2" href="{{route('admin.service.index')}}"><i class="fas fa-arrow-left"></i> Back</a>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form autocomplete="off" class="form-horizontal" id="engineerFrm" action="{{route('admin.engineer.store')}}" name="module_form" novalidate="novalidate">
                                <div class="modal-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="text-primary">General Information</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="form-group d-flex align-items:center">
                                                <select class="form-control form-control-sm" name="status" id="status">
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">InActive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input type="hidden" name="user_type" value="1">
                                            <input type="hidden" name="role_id" value="2">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="first_name">First name <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter first name" id="first_name" name="first_name">
                                                    <label id="first_name-error" class="text-danger" for="first_name"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="last_name">Last name<span class="red">*</span></label>
                                                    <input type="text"placeholder="Please enter last name" id="last_name" name="last_name" class="form-control">
                                                    <label id="last_name-error" class="text-danger" for="last_name"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="email">Email <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter email" id="email" name="email" >
                                                    <label id="email-error" class="text-danger" for="email"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="mobile">Mobile<span class="red">*</span></label>
                                                    <input type="text" placeholder="Please enter mobile"  id="mobile" name="mobile" class="form-control">
                                                    <label id="mobile-error" class="text-danger" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-4">
                                                <div class="form-group">
                                                    <label for="password">Password<span class="red">*</span></label>
                                                    <input type="password" placeholder="Please enter password"  id="password" name="password" class="form-control">
                                                    <label id="password-error" class="text-danger" for="password"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="reset" class="btn btn-dark">Reset</button>
                                    <button type="submit" class="btn btn-primary float-right" id="module_form_btn">Save<span style="display: none" id="users_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    <input type="hidden" id="module_edit_url" value="{{ route('admin.engineer.edit') }}">
</div>
@include('admin.engineer.modals')
<!-- /.content -->
@endsection
@push('script')
<script src="{{asset('public/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('public/asset/js/engineer.js')}}"></script>
@endpush