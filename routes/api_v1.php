<?php

use App\Http\Controllers\Api\V1\{
    AddressController,
    AuthController,
    EngineerSkillController,
    ProductController,
    ProfileController,
    ServiceBookingController,
    ServiceCategoryController,
    ServiceController,
    SkillController,
    StaticPageController,
    UserSkillController
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

Route::get('static-page/{slug}', [StaticPageController::class, 'index']);
Route::group(['prefix' => 'client'], function () {

    // Auth routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('refresh-token', 'refreshToken');
        Route::post('register', 'clientRegister');
        Route::post('verification', 'otpVerification');
        Route::post('resend-otp', 'resendVerificationOtp');
        Route::post('resend-activation', 'resendActivation');
        Route::post('forgot-password', 'forgotPassword');
        Route::post('reset-password', 'resetPassword');
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('profile', [ProfileController::class, 'profile']);
        Route::post('profile', [ProfileController::class, 'profileUpdate']);
        Route::post('change-password', [ProfileController::class, 'changePassword']);
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('address-type', [AddressController::class, 'addressType']);
        Route::resource('address', AddressController::class)->except(['create', 'edit']);
        Route::get('dashboard', [ProfileController::class, 'dashboard']);

        Route::get('service-category', [ServiceController::class, 'serviceCategory']);
        Route::get('services/{category_id}', [ServiceController::class, 'index']);
        Route::get('sub-services', [ServiceController::class, 'subServices']);
        Route::post('services/booking', [ServiceBookingController::class, 'create'])->name('services.booking.create');
    });
});


Route::group(['middleware' => ['auth:api']], function () {
    Route::get('skills', [SkillController::class, 'index']);
});

Route::group(['prefix' => 'engineer'], function () {

    // Auth routes
    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'engineerRegister');
        Route::post('forgot-password', 'forgotPassword');
        Route::post('reset-password', 'resetPassword');
        Route::post('resend-activation', 'resendActivation');
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('logout', [AuthController::class, 'logout']);
        Route::get('profile', [ProfileController::class, 'engineerProfile']);
        Route::post('profile', [ProfileController::class, 'engineerProfileUpdate']);
        Route::post('change-password', [ProfileController::class, 'changePassword']);
        //user skill manage
        Route::get('skills', [EngineerSkillController::class, 'index']);
        Route::post('skills/update', [EngineerSkillController::class, 'update']);
    });
});




Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum']], function () {
    Route::post('entitylogout', [AuthController::class, 'logout']);

    // Master module routes
    Route::resource('skill', SkillController::class)->except(['create', 'edit']);
    Route::resource('addresstype', AddressTypeController::class)->except(['create', 'edit']);
    Route::resource('itemcategory', ItemCategoryController::class)->except(['create', 'edit']);
    Route::resource('unitofmeasurment', UnitOfMeasurementController::class)->except(['create', 'edit']);
    Route::resource('entityuser', ManageEntityController::class)->except(['create', 'edit']);

    // Service category routes
    Route::get('servicecategory', [ServiceCategoryController::class, 'index']);
    Route::get('servicecategory/{id}', [ServiceCategoryController::class, 'show']);
    Route::post('servicecategory', [ServiceCategoryController::class, 'store']);
    Route::post('servicecategory/{id}', [ServiceCategoryController::class, 'update']);
    Route::delete('servicecategory/{id}', [ServiceCategoryController::class, 'destroy']);

    // Item routes
    Route::get('item', [ProductController::class, 'index']);
    Route::get('item/{id}', [ProductController::class, 'show']);
    Route::post('item', [ProductController::class, 'store']);
    Route::post('item/{id}', [ProductController::class, 'update']);
    Route::delete('item/{id}', [ProductController::class, 'destroy']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     // logout route
//     return $request->user();
// });

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {

    Route::post('entitylogout', [AuthController::class, 'logout']);
    Route::resource('skill', SkillController::class)->except(['store', 'update', 'show', 'create', 'edit']);
    Route::resource('addresstype', AddressTypeController::class)->except(['store', 'update', 'show', 'create', 'edit']);

    // Service category routes
    Route::get('servicecategory', [ServiceCategoryController::class, 'index']);
    Route::get('servicecategory/{id}', [ServiceCategoryController::class, 'show']);
    Route::get('states', [AuthController::class, 'getStates']);
});
