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
                        <div class="card-body p-4">
                            <form autocomplete="off" class="form-horizontal" id="module_form" action="{{route('admin.service.store')}}" name="module_form" novalidate="novalidate">
                                <div class="modal-body">
                                    <div class="card-body">
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
                                            <div class="col-sm-12 col-md-3">
                                                <div class="form-group">
                                                    <label for="campaign_category_id">Service Category<span class="red">*</span></label>
                                                    <select class="form-control serviceCategory" name="campaign_category_id" id="campaign_category_id">
                                                        <option selected value="">Select option</option>
                                                        @foreach ($getServiceCategoryData as $serviceCategory)
                                                        <option value="{{$serviceCategory->id}}">{{$serviceCategory->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="campaign_category_id-error" class="error" for="campaign_category_id"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                <div class="form-group">
                                                    <label for="campaign_category_id">Commission<span class="red">*</span></label>
                                                    <input type="text" name="commission" class="form-control">
                                                    <label id="campaign_category_id-error" class="error" for="campaign_category_id"></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="form-group">
                                                    <label for="description">Description </label>
                                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                                    <label id="description-error" class="error" for="description"></label>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <h4 class="text-primary">Skill Set</h4>
                                                    <select name="skills[]" id="skill" class="form-control serviceSkills">
                                                        <option value="">Select</option>
                                                        @foreach ($getSkillsData as $skill)
                                                        <option value="{{$skill->id}}">{{$skill->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label id="name-error" class="error" for="mobile"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <h4 class="text-primary">Images</h4>
                                                    <input type="file" name="image" id="select-image" multiple class="form-control image_input" placeholder="Enter Select Image" onchange="load_preview_image(this);" accept="image/png,image/jpg,image/jpeg">
                                                    <label id="image-error" class="error" style="display: none;" for="image"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <div class="preview_image">
                                                <!-- It will show the total number of files selected -->
                                                <p><span id="total-images">0</span> File(s) Selected</p>

                                                <!-- All images will display inside this div -->
                                                <div id="images"></div>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-primary">Sub Services</h4>
                                            </div>
                                            <div class="after-add-more col-md-12">
                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label for="name">Select Product <span class="red">*</span></label>
                                                            <select name="sub_service_products[]" id="" class="form-control items">
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
                                                            <select name="sub_service_uom[]" id="" class="form-control unitOfMeasurement">
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
                                                            <input type="checkbox" name="sub_service_qty_require[]">
                                                            <label id="name-error" class="error" for="mobile"></label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 change">
                                                        <label for=''> </label><br/><a class='btn btn-info add-more'>+ Add More</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="add-more-section col-md-12"></div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="text-primary">Price Section</h4>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">Price</label>
                                                    <input type="text" placeholder="Please enter price" class="form-control" name="service_price">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">From date </label>
                                                    <input type="date" class="form-control" name="service_price">
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
@push('script')
<script src="{{asset('public/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{asset('public/asset/js/service.js')}}"></script>
@endpush