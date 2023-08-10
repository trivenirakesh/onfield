<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;

use App\Http\Resources\V1\ServiceCategoryResource;
use App\Http\Resources\V1\ServiceResource;
use App\Services\V1\ScheduleService;
use App\Services\V1\ServiceCategoryService;
use App\Services\V1\ServicesService;
use App\Traits\CommonTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    use CommonTrait;


    public function index($categoryId)
    {
        try {
            $servicesService = new ServicesService;
            $result =  $servicesService->index($categoryId);
            if (!$result['status'] ?? true) {
                return response()->json($result, 404);
            }
            $result['data'] = ServiceResource::collection($result['data']);
            return response()->json($result, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function subServices()
    {
        try {
            // sub services call here under development
            return response()->json(['status' => true, 'messsage'  => 'success', 'data' => []], 200);
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

    public function bookingSchedule()
    {
        try {
            $scheduleService = new ScheduleService;
            $scheduleService =  $scheduleService->bookingSchedule();
            if (!$scheduleService['status']) {
                return response()->json($scheduleService, 404);
            }
            // $scheduleService['data'] = ScheduleServiceResource::collection($serviceCategory['data']);
            return response()->json($scheduleService, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }

    public function bookingScheduleByDate(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date_format:Y-m-d|after_or_equal:today',
            ]);

            if ($validator->fails()) {
                $message = $validator->messages()->first();
                return response()->json([
                    'status' => false,
                    'message' => $message,
                    'errors' => $validator->errors(),
                ], 404);
            }

            $scheduleService = new ScheduleService;
            $scheduleService =  $scheduleService->bookingScheduleByDate($request);
            if (!$scheduleService['status']) {
                return response()->json($scheduleService, 404);
            }
            return response()->json($scheduleService, 200);
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
