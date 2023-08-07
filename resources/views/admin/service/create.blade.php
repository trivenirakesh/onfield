@extends('admin.layouts.admin')
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
                        <div class="card-body p-4">
                            <form autocomplete="off" class="form-horizontal" id="module_form" action="{{route('admin.service.store')}}" name="module_form" novalidate="novalidate">
                                <div class="modal-body">
                                    <div class="card-body">
                                        <input type="hidden" name="id" id="id" value="0">
                                        <div class="row">
                                            <div class="form-group d-flex align-items:center">
                                                <select class="form-control form-control-sm" name="status" id="status">
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">InActive</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Name <span class="red">*</span></label>
                                                    <input type="text" class="form-control" placeholder="Please enter name" id="name" name="name" value="{{old('name')}}">
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="campaign_category_id">Service Category<span class="red">*</span></label>
                                                    <select class="form-control" name="campaign_category_id" id="campaign_category_id">
                                                        <option selected value="">Select option</option>
                                                        @foreach ($getServiceCategoryData as $serviceCategory)
                                                        <option value="{{$serviceCategory->id}}">{{$serviceCategory->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="campaign_category_id-error" class="error" for="campaign_category_id"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="description">Description <span class="red">*</span></label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                                    <label id="description-error" class="error" for="description"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-7 col-lg-8">
                                                        <div class="form-group">
                                                            <label for="image">Images</label>
                                                            <input type="file" name="image" id="image" class="form-control image_input" placeholder="Enter Select Image" onchange="load_preview_image(this);" accept="image/png,image/jpg,image/jpeg">
                                                            <label id="image-error" class="error" style="display: none;" for="image"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 col-md-5 col-lg-4">
                                                        <div id="preview_div" style="display: none;">
                                                            <img id="image_preview" style="width: auto;" height="70" class="profile-user-img" src="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Select Product <span class="red">*</span></label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach ($getProductsData as $product)
                                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="name">Select unit of measurement <span class="red">*</span></label>
                                                    <select name="" id="" class="form-control">
                                                        <option value="">Select</option>
                                                        @foreach ($getUomData as $uom)
                                                        <option value="{{$uom->id}}">{{$uom->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="name">In Require from client  <span class="red">*</span></label>
                                                <div class="form-group">
                                                    <input type="checkbox" name="">
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-center">
                                    <button type="submit" class="btn btn-primary" id="module_form_btn">Save<span style="display: none" id="users_form_loader"><i class="fa fa-spinner fa-spin"></i></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- /.content -->
@endsection