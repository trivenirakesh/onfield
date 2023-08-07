<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Api\ChangePasswordRequest;
use App\Http\Requests\V1\Api\ProfileRequest;
use App\Http\Resources\V1\ServiceCategoryResource;
use App\Http\Resources\V1\ServiceResource;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use App\Services\V1\ManageUserService;
use App\Services\V1\ServiceCategoryService;
use App\Services\V1\ServicesService;
use App\Traits\CommonTrait;
use Exception;
use Illuminate\Support\Facades\Request;

class ServiceController extends Controller
{
    use CommonTrait;


    public function index()
    {
        try {
            $servicesService = new ServicesService;
            $result =  $servicesService->index();
            if (!$result['status'] ?? true) {
                return response()->json($result, 404);
            }
            $result['data'] = ServiceResource::collection($result['data']);
            return response()->json($result, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function serviceCategory()
    {
        try {
            $serviceCategoryService = new ServiceCategoryService;
            $serviceCategory =  $serviceCategoryService->index() ?? [];
            if (!$serviceCategory['status']) {
                return response()->json($serviceCategory, 404);
            }
            $serviceCategory['data'] = ServiceCategoryResource::collection($serviceCategory['data']);
            return response()->json($serviceCategory, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function dashboard()
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
}
