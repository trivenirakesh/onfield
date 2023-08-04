<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CommonHelper;
use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\Item;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title =  'Service';
        return view('admin.service.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title =  'Service';
        $getServiceCategoryData = CommonHelper::getTableWiseData(ServiceCategory::class);
        $getItemsData = CommonHelper::getTableWiseData(Item::class);
        $getUomData = CommonHelper::getTableWiseData(UnitOfMeasurement::class);
        return view('admin.service.create', compact('title','getServiceCategoryData','getItemsData','getUomData'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
