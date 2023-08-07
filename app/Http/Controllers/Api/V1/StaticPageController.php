<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;

use App\Http\Resources\V1\UserResource;
use App\Services\V1\ManageUserService;
use App\Services\V1\StaticPageService;
use App\Traits\CommonTrait;
use Exception;

class StaticPageController extends Controller
{
    use CommonTrait;


    public function index($slug)
    {
        try {
            $userService = new StaticPageService;
            $getUserDetails = $userService->show($slug);
            if (!$getUserDetails['status']) {
                return $this->jsonResponse($getUserDetails, 401);
            }
            return $this->jsonResponse($getUserDetails, 200);
        } catch (Exception $th) {
            return $this->errorResponse(__('messages.failed.general'), 500);
        }
    }
}
