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
                            <form autocomplete="off" class="form-horizontal" id="module_form" action="{{route('admin.service.store')}}" name="module_form" novalidate="novalidate">
                                <div class="modal-body">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h3 class="text-primary">General Information</h3>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">First name <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter first name" id="name" name="name" value="{{old('name')}}">
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="campaign_category_id">Last name<span class="red">*</span></label>
                                                    <input type="text" name="commission" placeholder="Please enter last name"  class="form-control">
                                                    <label id="campaign_category_id-error" class="error" for="campaign_category_id"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Email <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter email" id="name" name="name" value="{{old('name')}}">
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="campaign_category_id">Mobile<span class="red">*</span></label>
                                                    <input type="text" name="commission" placeholder="Please enter mobile"  class="form-control">
                                                    <label id="campaign_category_id-error" class="error" for="campaign_category_id"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card" style="height: 250px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="text-primary">Qualification</h3>
                                </div>
                                <div class="col-md-6 ">
                                    <a href="javascript:void(0)" onclick="openModals('qualification-add-update')" class="float-right btn btn-primary"> + Add Qualification</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card " style="height: 250px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="text-primary">Experience / Training</h3>
                                </div>
                                <div class="col-md-6 ">
                                    <a href="javascript:void(0)" onclick="openModals('experience-add-update')" class="float-right btn btn-primary"> + Add Experience / Training</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card " style="height: 250px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-primary">Skill Set</h3>
                                </div>
                                <div class="col-md-12">
                                    <select name="" class="form-control" id="">
                                        <option value="">Select</option>
                                        <option value="">Hardware</option>
                                        <option value="">Software</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card " style="height: 250px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="text-primary">Address</h3>
                                </div>
                                <div class="col-md-6 ">
                                <a href="javascript:void(0)" onclick="openModals('address-add-update')" class="float-right btn btn-primary"> + Add Address</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card " style="height: 250px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="text-primary">Working hours</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card " style="height: 250px;">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h3 class="text-primary">Vacation</h3>
                                </div>
                                <div class="col-md-6 ">
                                    <button class="float-right btn btn-primary"> + Add Vacation</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card " style="height: 200px;">
                        <div class="card-body">
                            <h3 class="text-primary">Upload Document</h3>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-10"></div>
                <div class="col-md-2">
                    <input type="reset" value="Reset" class="btn btn-dark">
                    <input type="submit" value="Update" class="btn btn-primary">
                </div>
            </div>
        </div>
    </section>
</div>
@include('admin.engineer.modals')
<!-- /.content -->
@endsection
@push('script')
<script src="{{asset('public/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('public/asset/js/engineer.js')}}"></script>
@endpush