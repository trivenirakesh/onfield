<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ChangePasswordRequest;
use App\Http\Requests\V1\Api\ProfileRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\V1\ManageUserService;
use App\Traits\CommonTrait;
use Exception;
use Illuminate\Support\Facades\Request;

class ProfileController extends Controller
{
    use CommonTrait;


    public function index()
    {
        try {
            $popularProducts = [];
            $recentServices = [];
            $recentCalls = [];
            $response = [
                'recent_calls' => $recentCalls,
                'recent_services' => $recentServices,
                'popular_products' => $popularProducts,
            ];
            return $this->successResponse('Dashboard' . __('messages.success.details'), $response, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function profile(Request $request)
    {
        try {
            $userService = new ManageUserService;
            $getUserDetails = $userService->show(auth()->id());
            if (!$getUserDetails['status']) {
                return $this->jsonResponse($getUserDetails, 401);
            }
            $getUserDetails['data'] =  new UserResource($getUserDetails['data']);
            return $this->jsonResponse($getUserDetails, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }
    public function profileUpdate(ProfileRequest $request)
    {
        try {
            $inputs = $request->validated();
            $user = User::where('id', auth()->id())->first();
            $user->update($inputs);
            $user =  new UserResource($user);
            return $this->successResponse('Profile' . __('messages.success.update'), $user, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        try {
            $password = bcrypt($request->password);
            $inputs = $request->validated();
            $user = User::where('id', auth()->id())->first();
            $user->update(['password' => $password]);
            return $this->successResponse('Password' . __('messages.success.update'), 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }
}
