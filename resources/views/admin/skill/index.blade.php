@extends('admin.layouts.admin')
@section('content')
<div class="p-4">
    <div class="content-header">
        <div class="container-fluid d-flex justify-content-between  align-items-center">
            <h2 class="theme_primary_text d-inline-block">{{$title}}</h2>
            <div class="text-right d-inline-block">
                <!-- <button class="theme_primary_btn btn btn-sm float-right  ml-2" onclick="addModel()"><i class="fa fa-plus" aria-hidden="true"></i> Add {{$title}}</button> -->
                <a href="javascript:void(0)" onclick="globalFunctionModal('skill','Add Skill')" class="btn btn-primary fa fa-plus"> ADD SKILL</a>
            </div>
        </div>
    </div>
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body py-4 px-2">
                            <table id="data_table_main" class="table w-100">
                                <thead>
                                    <tr>
                                        <th class="text-center"></th>
                                        <th>Name</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" id="page_module" value="{{$title}}">
    <input type="hidden" id="module_index_url" value="{{ route('admin.skill.index') }}">
</div>

<!-- /.content -->

@endsection
@push('script')
<script src="{{asset('asset/js/skill.js')}}"></script>
@endpush