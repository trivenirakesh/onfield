<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ManageEngineerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title =  'Engineers';
        return view('admin.engineer.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title =  'Add Engineer';
        // $getSkillsData = CommonHelper::getTableWiseData(Skill::class);
        // $getServiceCategoryData = CommonHelper::getTableWiseData(ServiceCategory::class);
        // $getProductsData = CommonHelper::getTableWiseData(Product::class);
        // $getUomData = CommonHelper::getTableWiseData(UnitOfMeasurement::class);
        return view('admin.engineer.create', compact('title'));
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
