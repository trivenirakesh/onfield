<?php

use App\Http\Controllers\API\V1\{
    AuthController,
    ItemController,
    ServiceCategoryController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/user/register', [AuthController::class, 'createUser']);
Route::post('/user/login', [AuthController::class, 'loginUser']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
    Route::post('userlogout',[AuthController::class,'logout']);
    
    // Master module routes
    Route::resource('skill',SkillController::class)->except(['create','edit']);
    Route::resource('addresstype',AddressTypeController::class)->except(['create','edit']);
    Route::resource('itemcategory',ItemCategoryController::class)->except(['create','edit']);
    Route::resource('unitofmeasurment',UnitOfMeasurementController::class)->except(['create','edit']);
    Route::resource('user',ManageUserController::class)->except(['create','edit']);

    // Service category routes
    Route::get('servicecategory',[ServiceCategoryController::class,'index']);
    Route::get('servicecategory/{id}',[ServiceCategoryController::class,'show']);
    Route::post('servicecategory',[ServiceCategoryController::class,'store']);
    Route::post('servicecategory/{id}',[ServiceCategoryController::class,'update']);
    Route::delete('servicecategory/{id}',[ServiceCategoryController::class,'destroy']);

    // Item routes
    Route::get('item',[ItemController::class,'index']);
    Route::get('item/{id}',[ItemController::class,'show']);
    Route::post('item',[ItemController::class,'store']);
    Route::post('item/{id}',[ItemController::class,'update']);
    Route::delete('item/{id}',[ItemController::class,'destroy']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     // logout route
//     return $request->user();
// });

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {
    
    Route::post('userlogout',[AuthController::class,'logout']);
    Route::resource('skill',SkillController::class)->except(['store','update','show','create','edit']);
    Route::resource('addresstype',AddressTypeController::class)->except(['store','update','show','create','edit']);

    // Service category routes
    Route::get('servicecategory',[ServiceCategoryController::class,'index']);
    Route::get('servicecategory/{id}',[ServiceCategoryController::class,'show']);
    Route::get('states',[AuthController::class,'getStates']);
});
