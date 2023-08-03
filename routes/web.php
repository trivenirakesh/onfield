<?php

use App\Http\Controllers\Admin\{AddressTypeController, HomeController, ItemCategoryController, ServiceCategoryController, SkillController, UnitOfMeasurementController};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Auth::routes(['register' => false]);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin'], 'as' => 'admin.'], function () {
    Route::get('/',[HomeController::class,'index'])->name('dashboard');

    // Master module routes 
    Route::resource('skill',SkillController::class)->except(['edit', 'update']);
    Route::resource('addresstype',AddressTypeController::class)->except(['edit','update']);
    Route::resource('itemcategory',ItemCategoryController::class)->except(['edit','update']);
    Route::resource('unitofmeasurement',UnitOfMeasurementController::class)->except(['edit','update']);
    Route::resource('servicecategory',ServiceCategoryController::class)->except(['edit','update']);
});
Route::get('logout', [HomeController::class, 'logout'])->name('logout');
