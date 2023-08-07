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
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header px-4 py-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">Profile</a></li>
                                <li class="nav-item"><a class="nav-link" href="#password" data-toggle="tab">Password</a></li>
                            </ul>
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane active" id="profile">
                                        <form id="profile_frm" form_name="profile_frm" method="post" action="{{ route('admin.profile-update') }}">
                                            <div class="card-body">
                                                <input type="hidden" name="id" id="id" value="{{$user->id}}">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Firstname <span class="red">*</span></label>
                                                            <input type="text" class="form-control letter-accept" placeholder="Please enter firstname" id="first_name" name="first_name" value="{{$user->first_name}}">
                                                            <label id="first_name-error" class="text-danger" style="display: none;" for="first_name"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Lastname <span class="red">*</span></label>
                                                            <input type="text" class="form-control letter-accept" placeholder="Please enter lastname" id="last_name" name="last_name" value="{{$user->last_name}}">
                                                            <label id="last_name-error" class="text-danger" style="display: none;" for="last_name"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Email <span class="red">*</span></label>
                                                            <input name="email" type="email" class="form-control" placeholder="Please enter email" value="{{$user->email}}">
                                                            <label id="email-error" class="text-danger" style="display: none;" for="email"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Mobile <span class="red">*</span></label>
                                                            <input type="number" class="form-control numbers-accept" placeholder="Please enter mobile" id="mobile" name="mobile" value="{{$user->mobile}}">
                                                            <label id="mobile-error" class="text-danger" style="display: none;" for="mobile"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-flat float-right" id="profile_frm_btn">Update Profile <span style="display: none" id="profile_frm_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="tab-pane" id="password">
                                        <form id="password_frm" form_name="password_frm" method="post" action="{{ route('admin.update-password') }}">
                                            <div class="card-body">
                                                <input type="hidden" name="id" id="id" value="{{$user->id}}">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Old Password <span class="red">*</span></label>
                                                            <input type="password" class="form-control" placeholder="Please enter old password" id="old_password" name="old_password">
                                                            <label id="old_password-error" class="text-danger" for="old_password"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Password <span class="red">*</span></label>
                                                            <input type="password" class="form-control" placeholder="Please enter password" id="password" name="password">
                                                            <label id="password-error" class="text-danger" for="password"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label>Confirm Password <span class="red">*</span></label>
                                                            <input type="password" class="form-control" placeholder="Please enter confirm password" id="password_confirmation" name="password_confirmation">
                                                            <label id="password_confirmation-error" class="text-danger" for="password_confirmation"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary btn-flat float-right" id="password_frm_btn">Update Password <span style="display: none" id="password_frm_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
</div>

<!-- /.content -->

@endsection
@push('script')
<script src="{{asset('public/asset/js/profile.js')}}"></script>
@endpush